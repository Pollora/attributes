<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the exclude_from_search parameter for a post type.
 *
 * When set to true, the post type will be excluded from search results.
 * When set to false, the post type will be included in search results.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ExcludeFromSearch extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type should be excluded from search results
     */
    public function __construct(
        private readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the exclude_from_search parameter.
     *
     * @param  PostType  $postType  The post type to configure
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['exclude_from_search'] = $this->value;
    }
}
