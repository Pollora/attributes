<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the block_editor parameter for a post type.
 *
 * When set to true, the Gutenberg block editor will be used for this post type.
 * When set to false, the classic editor will be used instead.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class BlockEditor extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to use the block editor for this post type
     */
    public function __construct(
        private bool $value = true
    ) {}

    /**
     * Configure the post type with the block_editor parameter.
     *
     * @param  PostType  $postType  The post type to configure
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['show_in_rest'] = $this->value;
    }
}
