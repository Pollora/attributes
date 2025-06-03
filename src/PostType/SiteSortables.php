<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the site_sortables parameter for a post type.
 *
 * An array of sortable columns for front-end sorting.
 * This is a feature provided by the Extended CPTs library.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class SiteSortables extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of site sortables
     */
    public function __construct(
        private readonly array $value
    ) {}

    /**
     * Configure the post type with the site_sortables parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['site_sortables'] = $this->value;
    }
}
