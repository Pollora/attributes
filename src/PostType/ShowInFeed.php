<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the show_in_feed parameter for a post type.
 *
 * When set to true, posts of this type will be included in the site's main RSS feed.
 * When set to false, posts of this type will be excluded from the feed.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInFeed extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to include this post type in the RSS feed
     */
    public function __construct(
        private bool $value = true
    ) {}

    /**
     * Configure the post type with the show_in_feed parameter.
     *
     * @param  PostType  $postType  The post type to configure
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['show_in_feed'] = $this->value;
    }
}
