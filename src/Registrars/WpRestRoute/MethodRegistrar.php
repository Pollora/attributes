<?php
namespace Pollora\Attributes\Registrars\WpRestRoute;

use Pollora\Attributes\Attributable;
use Pollora\Attributes\WpRestRoute\Method;
use Pollora\Support\Facades\Action;
use ReflectionMethod;
use WP_REST_Request;
use WP_Error;

class MethodRegistrar
{
    /**
     * Handles the registration of the REST route for the method.
     *
     * @param Attributable $instance The class instance
     * @param ReflectionMethod $method The method reflection
     * @param Method $methodAttribute The method attribute instance
     * @return void
     */
    public static function handle(Attributable $instance, ReflectionMethod $method, Method $methodAttribute): void
    {
        $methodPermission = $methodAttribute->permissionCallback;
        $permissionCallback = $methodPermission ?? $instance->classPermission;

        Action::add('rest_api_init', function () use ($instance, $method, $permissionCallback, $methodAttribute) {
            register_rest_route(
                $instance->namespace,
                $instance->route,
                [
                    'methods'  => $methodAttribute->getMethods(),
                    'callback' => fn(WP_REST_Request $request) => self::handleRequest($instance, $method, $request),
                    'args'     => self::extractArgsFromRoute($instance->route),
                    'permission_callback' => self::resolvePermissionCallback($permissionCallback),
                ]
            );
        });
    }

    /**
     * Executes the class method associated with the REST request.
     *
     * @param Attributable $instance The class instance
     * @param ReflectionMethod $method The method to invoke
     * @param WP_REST_Request $request The REST request instance
     * @return mixed The result of the method invocation
     */
    private static function handleRequest(Attributable $instance, ReflectionMethod $method, WP_REST_Request $request)
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
