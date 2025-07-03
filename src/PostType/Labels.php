<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the label parameter for a post type.
 *
 * The label parameter is a general name for the post type, usually plural.
 * This is used in various places in the WordPress admin.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Labels extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  string  $value  The general name for the post type, usually plural
     */
    public function __construct(
        public readonly array $value
    ) {}

    /**
     * Configure the post type with the label parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['labels'] = $this->value;
    }
}
