<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use ReflectionClass;
use ReflectionMethod;

/**
 * Attribute to declare a route in the WordPress REST API.
 *
 * @param  string  $namespace  The namespace for the REST API route.
 * @param  string  $route  The specific route within the namespace.
 * @param  string|null  $permissionCallback  The callback function to check permissions for the route.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class WpRestRoute implements HandlesAttributes
{
    public function __construct(
        public string $namespace,
        public string $route,
        public ?string $permissionCallback = null
    ) {}

    public function handle(object $instance, ReflectionClass|ReflectionMethod $reflection, object $routeAttribute): void
    {
        $instance->namespace = $routeAttribute->namespace;
        $instance->route = $routeAttribute->route;
        $instance->classPermission = $routeAttribute->permissionCallback;
    }
}
