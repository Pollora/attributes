<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Pollora\Attributes\Contracts\HandlesAttributes;
use ReflectionClass;
use ReflectionMethod;

/**
 * Attribute for defining WordPress REST API routes.
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
        public readonly string $namespace,
        public readonly string $route,
        public readonly ?string $permissionCallback = null
    ) {}

    /**
     * Handle the attribute processing.
     *
     * @param  object  $serviceLocator  Service locator used to resolve dependencies
     * @param  object  $instance  The instance to which the attribute applies
     * @param  ReflectionClass|ReflectionMethod  $context  The reflection context.
     * @param  object  $attribute  The attribute instance containing provided arguments.
     */
    public function handle($serviceLocator, object $instance, ReflectionClass|ReflectionMethod $context, object $attribute): void
    {
        $instance->namespace = $attribute->namespace;
        $instance->route = $attribute->route;
        $instance->classPermission = $attribute->permissionCallback;
    }
}
