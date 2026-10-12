<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the capabilities parameter for a post type.
 *
 * Provides an array of capabilities for this post type.
 * See WordPress documentation for the full list of capabilities.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Capabilities implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  array  $value  The capabilities array for the post type
     */
    public function __construct(
        public readonly array $value
    ) {}

    /**
     * Configure the post type with the capabilities parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['capabilities'] = $this->value;

        return $arguments;
    }
}
