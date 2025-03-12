<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the public parameter for a post type.
 *
 * When set to true, the post type will be publicly accessible.
 * When set to false, the post type will be private.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class PublicPostType extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type should be public
     */
    public function __construct(
        private bool $value = true
    ) {}

    /**
     * Configure the post type with the public parameter.
     *
     * @param  PostType  $postType  The post type to configure
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['public'] = $this->value;
    }
}
