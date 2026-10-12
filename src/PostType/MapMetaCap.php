<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the map_meta_cap parameter for a post type.
 *
 * When set to true, WordPress will map meta capabilities to primitive capabilities.
 * This is usually used together with the CapabilityType attribute.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class MapMetaCap implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to map meta capabilities
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the map_meta_cap parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['map_meta_cap'] = $this->value;

        return $arguments;
    }
}
