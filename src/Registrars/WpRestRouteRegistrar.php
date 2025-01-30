<?php
namespace Pollora\Attributes\Registrars;

use Pollora\Attributes\WpRestRoute;
use Pollora\Attributes\WpRestRoute\Method;
use Pollora\Attributes\WpRestRoute\Permission;
use ReflectionClass;
use ReflectionMethod;
use Pollora\Support\Facades\Action;
use WP_REST_Request;
use WP_Error;

class WpRestRouteRegistrar
{
    /**
     * Analyzes classes with the #[WpRestRoute] attribute and registers their REST endpoints.
     *
     * @param object $instance The route class instance
     * @param ReflectionClass $reflection The reflection of the class
     * @param WpRestRoute $routeAttribute The route attribute instance
     * @return void
     */
    public static function handle(object $instance, ReflectionClass $reflection, WpRestRoute $routeAttribute): void
    {
        $namespace = $routeAttribute->namespace;
        $routePath = $routeAttribute->route;
        $classPermission = $routeAttribute->permissionCallback; // Class-level permission

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($method->getAttributes(Method::class) as $attribute) {
                $methodAttribute = $attribute->newInstance();
                $methodPermission = $methodAttribute->permissionCallback; // Method-level permission

                // If a specific permission is defined at the action level, it overrides the route permission
                $permissionCallback = $methodPermission ?? $classPermission;

                Action::add('rest_api_init', function () use (
                    $namespace, $routePath, $instance, $method, $methodAttribute, $permissionCallback
                ) {
                    register_rest_route(
                        $namespace,
                        $routePath,
                        [
                            'methods'  => $methodAttribute->methods,
                            'callback' => fn(WP_REST_Request $request) => self::handleRequest($instance, $method, $request),
                            'args'     => self::extractArgsFromRoute($routePath),
                            'permission_callback' => self::resolvePermissionCallback($permissionCallback),
                        ]
                    );
                });
            }
        }
    }

    /**
     * Executes the class method associated with the REST request.
     *
     * @param object $instance The class instance
     * @param ReflectionMethod $method The method to invoke
     * @param WP_REST_Request $request The REST request instance
     * @return mixed The result of the method invocation
     */
    private static function handleRequest(object $instance, ReflectionMethod $method, WP_REST_Request $request)
    {
        $args = [];

        foreach ($method->getParameters() as $param) {
            $paramName = $param->getName();
            $args[] = $request->get_param($paramName) ?? null;
        }

        return $method->invoke($instance, ...$args);
    }

    /**
     * Extracts dynamic arguments from a WordPress route.
     *
     * @param string $route The route pattern
     * @return array The extracted arguments
     */
    private static function extractArgsFromRoute(string $route): array
    {
        preg_match_all('/\\(\\?P<([a-zA-Z0-9_]+)>/', $route, $matches);
        return array_fill_keys($matches[1], [
            'validate_callback' => fn($param) => is_string($param) || is_numeric($param),
        ]);
    }

    /**
     * Resolves and executes the permission callback.
     *
     * @param string|null $permissionCallback The permission class to use
     * @return callable The permission function
     */
    private static function resolvePermissionCallback(?string $permissionCallback): callable
    {
        if ($permissionCallback === null) {
            return '__return_true';
        }

        if (!class_exists($permissionCallback) || !is_subclass_of($permissionCallback, Permission::class)) {
            return fn() => new WP_Error('rest_forbidden', __('Invalid permission handler.'), ['status' => 403]);
        }

        return function (WP_REST_Request $request) use ($permissionCallback) {
            $permissionInstance = new $permissionCallback();
            return $permissionInstance->allow($request);
        };
    }
}
