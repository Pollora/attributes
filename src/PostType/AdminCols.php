<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the admin_cols parameter for a post type.
 *
 * Configures the columns displayed in the admin list table for this post type.
 * This is a feature provided by the Extended CPTs library.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AdminCols extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of admin columns configuration
     */
    public function __construct(
        private array $value
    ) {}

    /**
     * Configure the post type with the admin_cols parameter.
     *
     * @param  PostType  $postType  The post type to configure
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['admin_cols'] = $this->value;
    }
}
