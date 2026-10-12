<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the show_in_feed parameter for a post type.
 *
 * When set to true, posts of this type will be included in the site's main RSS feed.
 * When set to false, posts of this type will be excluded from the feed.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInFeed implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to include this post type in the RSS feed
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the show_in_feed parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['show_in_feed'] = $this->value;

        return $arguments;
    }
}
