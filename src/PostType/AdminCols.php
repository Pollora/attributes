<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

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
        private readonly array $value
    ) {}

    /**
     * Configure the post type with the admin_cols parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['admin_cols'] = $this->value;
    }
}
