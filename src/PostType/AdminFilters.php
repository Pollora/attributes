<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the admin_filters parameter for a post type.
 *
 * An array of taxonomy filters to display on the admin list screen.
 * This is a feature provided by the Extended CPTs library.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AdminFilters extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of admin filters
     */
    public function __construct(
        private readonly array $value
    ) {}

    /**
     * Configure the post type with the admin_filters parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['admin_filters'] = $this->value;
    }
}
