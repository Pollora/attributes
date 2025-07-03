<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

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
     * @param  string  $value  The custom featured image label
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the post type with the featured_image parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['featured_image'] = $this->value;
    }
}
