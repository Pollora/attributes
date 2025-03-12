<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the rewrite parameter for a post type.
 *
 * Controls the permalink structure for the post type.
 * - true: Uses the post type name as the slug
 * - false: Disables permalinks for this post type
 * - array: Custom rewrite rules (see WordPress documentation)
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Rewrite extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool|array  $value  The rewrite configuration for the post type
     */
    public function __construct(
        private bool|array $value = true
    ) {}

    /**
     * Configure the post type with the rewrite parameter.
     *
     * @param  PostType  $postType  The post type to configure
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['rewrite'] = $this->value;
    }
}
