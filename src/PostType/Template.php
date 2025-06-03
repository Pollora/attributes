<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the template parameter for a post type.
 *
 * An array of blocks to use as the default initial state for a post.
 * Each item is an array containing block name and optional attributes.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Template extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of blocks to use as the default template
     */
    public function __construct(
        private readonly array $value
    ) {}

    /**
     * Configure the post type with the template parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['template'] = $this->value;
    }
}
