<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the supports parameter for a post type.
 *
 * Defines which features the post type supports (title, editor, comments, etc.).
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Supports extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  array<string>  $features  Array of features the post type supports
     */
    public function __construct(
        private readonly array $features = ['title', 'editor']
    ) {}

    /**
     * Configure the post type with the supports parameter.
     *
     * @param  PostType  $postType  The post type to configure
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['supports'] = $this->features;
    }
}
