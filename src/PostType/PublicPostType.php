<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the public parameter for a post type.
 *
 * When set to true, the post type will be publicly accessible.
 * When set to false, the post type will be private.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class PublicPostType implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type should be public
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the public parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['public'] = $this->value;

        return $arguments;
    }
}
