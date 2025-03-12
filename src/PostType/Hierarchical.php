<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the hierarchical parameter for a post type.
 *
 * When set to true, the post type will be hierarchical like pages (can have parent/child relationships).
 * When set to false, the post type will be non-hierarchical like posts.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Hierarchical extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param bool $value Whether the post type should be hierarchical
     */
    public function __construct(
        private bool $value = true
    ) {
    }

    /**
     * Configure the post type with the hierarchical parameter.
     *
     * @param PostType $postType The post type to configure
     *
     * @return void
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['hierarchical'] = $this->value;
    }
} 