<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the chronological parameter for a post type.
 *
 * Sets the post type to be displayed in chronological order (newest first)
 * in admin lists and queries. This is a convenience attribute that sets
 * the default ordering to date, in descending order.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Chronological extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to display the post type chronologically
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the chronological parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        if ($this->value) {
            // Set default ordering to date, descending
            $postType->attributeArgs['orderby'] = 'date';
            $postType->attributeArgs['order'] = 'DESC';
        }
    }
}
