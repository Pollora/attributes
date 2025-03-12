<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

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
     * @param array $value An array of blocks to use as the default template
     */
    public function __construct(
        private array $value
    ) {
    }

    /**
     * Configure the post type with the template parameter.
     *
     * @param PostType $postType The post type to configure
     *
     * @return void
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['template'] = $this->value;
    }
} 