<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

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
     * @param  bool  $value  Whether the post type should be hierarchical
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the hierarchical parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['hierarchical'] = $this->value;
    }
}
