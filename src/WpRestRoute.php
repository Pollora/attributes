<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Pollora\Attributes\Contracts\HandlesAttributes;
use Pollora\Attributes\WpRestRoute\Permission;
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
     * @param  class-string<Permission>|Permission|null  $permissionCallback  The permission for the route: a Permission class, or an instance such as `new Can('edit_posts')`.
     */
    public function __construct(
        public readonly string $namespace,
        public readonly string $route,
        public readonly string|Permission|null $permissionCallback = null
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
