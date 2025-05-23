<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the site_filters parameter for a post type.
 *
 * An array of taxonomy filters for front-end filtering.
 * This is a feature provided by the Extended CPTs library.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class SiteFilters extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of site filters
     */
    public function __construct(
        private readonly array $value
    ) {}

    /**
     * Configure the post type with the site_filters parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['site_filters'] = $this->value;
    }
}
