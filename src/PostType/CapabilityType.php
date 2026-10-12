<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the capability_type parameter for a post type.
 *
 * The capability_type parameter is used as a base to build the capabilities that users
 * need to edit, delete, and read posts of this type.
 *
 * Common values:
 * - 'post' - Use post capabilities (default)
 * - 'page' - Use page capabilities
 * - custom value - Create custom capabilities based on this value
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class CapabilityType implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The capability type to use for this post type
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the post type with the capability_type parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['capability_type'] = $this->value;

        return $arguments;
    }
}
