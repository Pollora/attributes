<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

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
     * @param array $value An array of site filters
     */
    public function __construct(
        private array $value
    ) {
    }

    /**
     * Configure the post type with the site_filters parameter.
     *
     * @param PostType $postType The post type to configure
     *
     * @return void
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['site_filters'] = $this->value;
    }
} 