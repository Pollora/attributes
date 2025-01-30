<?php
namespace Pollora\Attributes;

use Attribute;

/**
 * Attribute to declare a route in the WordPress REST API.
 *
 * @param string $namespace The namespace for the REST API route.
 * @param string $route The specific route within the namespace.
 * @param string|null $permissionCallback The callback function to check permissions for the route.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class WpRestRoute
{
    public function __construct(
        public string $namespace,
        public string $route,
        public ?string $permissionCallback = null
    ) {}
}
