<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the capability_type parameter for a post type.
 *
 * The capability_type parameter is used as a base to build the capabilities that users
 * need to edit, delete, and read posts of this type.
 *
 * Common values:
 * - 'post' - Use post capabilities (default)
 * - 'page' - Use page capabilities
 * - custom value - Create custom capabilities based on this value
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class CapabilityType extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  string  $value  The capability type to use for this post type
     */
    public function __construct(
        private string $value
    ) {}

    /**
     * Configure the post type with the capability_type parameter.
     *
     * @param  PostType  $postType  The post type to configure
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['capability_type'] = $this->value;
    }
}
