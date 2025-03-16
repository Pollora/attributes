<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use ReflectionClass;
use ReflectionMethod;

/**
 * Attribute to declare a route in the WordPress REST API.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class WpRestRoute implements HandlesAttributes
{
    /**
     * Constructor for WpRestRoute attribute.
     *
     * @param  string  $namespace  The namespace for the REST API route (e.g., "my-plugin/v1").
     * @param  string  $route  The specific route within the namespace (e.g., "/items").
     * @param  string|null  $permissionCallback  Optional callback method name to check permissions for the route.
     */
    public function __construct(
        public string $namespace,
        public string $route,
        public ?string $permissionCallback = null
    ) {}

    /**
     * Handles the initialization and assignment of route metadata.
     *
     * @param  object  $instance  The class instance where the attribute is used.
     * @param  ReflectionClass|ReflectionMethod  $context  The reflection context of the attribute usage.
     * @param  object  $attribute  The attribute instance containing provided arguments.
     */
    public function handle(object $instance, ReflectionClass|ReflectionMethod $context, object $attribute): void
    {
        $instance->namespace = $attribute->namespace;
        $instance->route = $attribute->route;
        $instance->classPermission = $attribute->permissionCallback;
    }
}
