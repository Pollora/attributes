<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the label parameter for a post type.
 *
 * The label parameter is a general name for the post type, usually plural.
 * This is used in various places in the WordPress admin.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Label extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param string $value The general name for the post type, usually plural
     */
    public function __construct(
        private string $value
    ) {
    }

    /**
     * Configure the post type with the label parameter.
     *
     * @param PostType $postType The post type to configure
     *
     * @return void
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['label'] = $this->value;
    }
} 