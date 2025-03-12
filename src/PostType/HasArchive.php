<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the has_archive parameter for a post type.
 *
 * When set to true, the post type will have an archive page.
 * Can also be set to a string to customize the archive slug.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class HasArchive extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool|string  $value  Whether the post type should have an archive or the archive slug
     */
    public function __construct(
        private bool|string $value = true
    ) {}

    /**
     * Configure the post type with the has_archive parameter.
     *
     * @param  PostType  $postType  The post type to configure
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['has_archive'] = $this->value;
    }
}
