<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the hierarchical parameter for a post type.
 *
 * When set to true, the post type will be hierarchical like pages (can have parent/child relationships).
 * When set to false, the post type will be non-hierarchical like posts.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Hierarchical implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type should be hierarchical
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the hierarchical parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['hierarchical'] = $this->value;

        return $arguments;
    }
}
