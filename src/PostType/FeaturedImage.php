<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the featured_image parameter for a post type.
 *
 * Sets a custom featured image label.
 * This is a feature provided by the Extended CPTs library.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class FeaturedImage extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param string $value The custom featured image label
     */
    public function __construct(
        private string $value
    ) {
    }

    /**
     * Configure the post type with the featured_image parameter.
     *
     * @param PostType $postType The post type to configure
     *
     * @return void
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['featured_image'] = $this->value;
    }
} 