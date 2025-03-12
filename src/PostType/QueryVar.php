<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the query_var parameter for a post type.
 *
 * Sets the query_var key for this post type.
 * - true: Sets to the post type's name
 * - false: Disables query_var
 * - string: Sets a custom query_var key
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class QueryVar extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param bool|string $value The query_var value for the post type
     */
    public function __construct(
        private bool|string $value = true
    ) {
    }

    /**
     * Configure the post type with the query_var parameter.
     *
     * @param PostType $postType The post type to configure
     *
     * @return void
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['query_var'] = $this->value;
    }
} 