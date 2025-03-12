<?php

declare(strict_types=1);

namespace Pollora\Attributes\WpRestRoute;

use Attribute;
use InvalidArgumentException;
use Pollora\Attributes\Attributable;
use Pollora\Attributes\HandlesAttributes;
use Pollora\Support\Facades\Action;
use ReflectionClass;
use ReflectionMethod;
use WP_Error;
use WP_REST_Request;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Method implements HandlesAttributes
{
    private const ALLOWED_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

    /**
     * Constructor for the Method attribute.
     *
     * @param  array|string  $methods  The HTTP methods allowed for this route.
     * @param  string|null  $permissionCallback  The callback function to check permissions for the route.
     *
     * @throws InvalidArgumentException If an invalid HTTP method is provided.
     */
    public function __construct(
        public array|string $methods,
        public ?string $permissionCallback = null
    ) {
        $this->methods = is_array($methods) ? $methods : [$methods];
        $this->validateMethods();
    }

    /**
     * Validates the provided HTTP methods.
     *
     * @throws InvalidArgumentException If an invalid HTTP method is found.
     */
    private function validateMethods(): void
    {
        foreach ($this->methods as $method) {
            if (! in_array(strtoupper($method), self::ALLOWED_METHODS)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Invalid HTTP method "%s". Allowed methods are: %s',
                        $method,
                        implode(', ', self::ALLOWED_METHODS)
                    )
                );
            }
        }
    }

    /**
     * Retrieves the list of HTTP methods.
     *
     * @return array The list of allowed HTTP methods.
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Handles the registration of the REST route for the method.
     *
     * @param  Attributable  $instance  The class instance
     * @param  ReflectionMethod  $method  The method reflection
     * @param  Method  $methodAttribute  The method attribute instance
     */
    public function handle(Attributable $instance, ReflectionMethod|ReflectionClass $method, object $methodAttribute): void
    {
        $methodPermission = $methodAttribute->permissionCallback;
        $permissionCallback = $methodPermission ?? $instance->classPermission;

        Action::add('rest_api_init', function () use ($instance, $method, $permissionCallback, $methodAttribute) {
            register_rest_route(
                $instance->namespace,
                $instance->route,
                [
                    'methods' => $methodAttribute->getMethods(),
                    'callback' => fn (WP_REST_Request $request) => self::handleRequest($instance, $method, $request),
                    'args' => self::extractArgsFromRoute($instance->route),
                    'permission_callback' => self::resolvePermissionCallback($permissionCallback),
                ]
            );
        });
    }

    /**
     * Executes the class method associated with the REST request.
     *
     * @param  Attributable  $instance  The class instance
     * @param  ReflectionMethod  $method  The method to invoke
     * @param  WP_REST_Request  $request  The REST request instance
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
     * @param  string  $route  The route pattern
     * @return array The extracted arguments
     */
    private static function extractArgsFromRoute(string $route): array
    {
        preg_match_all('/\\(\\?P<([a-zA-Z0-9_]+)>/', $route, $matches);

        return array_fill_keys($matches[1], [
            'validate_callback' => fn ($param) => is_string($param) || is_numeric($param),
        ]);
    }

    /**
     * Resolves and executes the permission callback.
     *
     * @param  string|null  $permissionCallback  The permission class to use
     * @return callable The permission function
     */
    private static function resolvePermissionCallback(?string $permissionCallback): callable
    {
        if ($permissionCallback === null) {
            return '__return_true';
        }

        if (! class_exists($permissionCallback) || ! is_subclass_of($permissionCallback, Permission::class)) {
            return fn () => new WP_Error('rest_forbidden', __('Invalid permission handler.'), ['status' => 403]);
        }

        return function (WP_REST_Request $request) use ($permissionCallback) {
            $permissionInstance = new $permissionCallback;

            return $permissionInstance->allow($request);
        };
    }
}
