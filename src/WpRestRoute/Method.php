<?php

declare(strict_types=1);

namespace Pollora\Attributes\WpRestRoute;

use Attribute;
use InvalidArgumentException;
use Pollora\Attributes\Attributable;
use Pollora\Attributes\Contracts\HandlesAttributes;
use ReflectionClass;
use ReflectionMethod;
use WP_Error;
use WP_REST_Request;
use Pollora\Support\WpGlobals;

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
            if (! in_array(strtoupper((string) $method), self::ALLOWED_METHODS)) {
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
     * @param  object  $serviceLocator  Le service locator pour résoudre les dépendances
     * @param  Attributable  $instance  The class instance
     * @param  ReflectionMethod|ReflectionClass  $context  The reflection context
     * @param  object  $attribute  The attribute instance
     */
    public function handle($serviceLocator, Attributable $instance, ReflectionMethod|ReflectionClass $context, object $attribute): void
    {
        $methodPermission = $attribute->permissionCallback;
        $permissionCallback = $methodPermission ?? $instance->classPermission;

        register_rest_route(
            $instance->namespace,
            $instance->route,
            [
                'methods' => $attribute->getMethods(),
                'callback' => WpGlobals::wrap(
                    fn (WP_REST_Request $request) => $this->handleRequest($instance, $context, $request)
                ),
                'args' => $this->extractArgsFromRoute($instance->route),
                'permission_callback' => $this->resolvePermissionCallback($permissionCallback),
            ]
        );
    }

    /**
     * Executes the class method associated with the REST request.
     *
     * @param  Attributable  $instance  The class instance
     * @param  ReflectionMethod  $method  The method to invoke
     * @param  WP_REST_Request  $request  The REST request instance
     * @return mixed The result of the method invocation
     */
    private function handleRequest(Attributable $instance, ReflectionMethod $method, WP_REST_Request $request): mixed
    {
        $args = [];

        foreach ($method->getParameters() as $param) {
            $paramName = $param->getName();
            $args[] = $request->get_param($paramName);
        }

        return $method->invoke($instance, ...$args);
    }

    /**
     * Extracts dynamic arguments from a WordPress route.
     *
     * @param  string  $route  The route pattern
     * @return array The extracted arguments
     */
    private function extractArgsFromRoute(string $route): array
    {
        preg_match_all('/\(\?P<(\w+)>/', $route, $matches);

        return array_fill_keys($matches[1] ?? [], [
            'validate_callback' => fn ($param): bool => is_string($param) || is_numeric($param),
        ]);
    }

    /**
     * Resolves and executes the permission callback.
     *
     * @param  string|null  $permissionCallback  The permission class to use
     * @return callable The permission function
     */
    private function resolvePermissionCallback(?string $permissionCallback): callable
    {
        if ($permissionCallback === null) {
            return '__return_true';
        }

        if (! class_exists($permissionCallback) || ! is_subclass_of($permissionCallback, Permission::class)) {
            return fn (): \WP_Error => new WP_Error('rest_forbidden', __('Invalid permission handler.'), ['status' => 403]);
        }

        return WpGlobals::wrap(function (WP_REST_Request $request) use ($permissionCallback): bool|\WP_Error {
            $permissionInstance = new $permissionCallback;
            return $permissionInstance->allow($request);
        });
    }
}
