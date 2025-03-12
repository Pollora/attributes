<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the rest_namespace parameter for a post type.
 *
 * Sets the namespace for the REST API endpoints for this post type.
 * By default, this is 'wp/v2'.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class RestNamespace extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param string $value The REST API namespace for the post type
     */
    public function __construct(
        private string $value
    ) {
    }

    /**
     * Configure the post type with the rest_namespace parameter.
     *
     * @param PostType $postType The post type to configure
     *
     * @return void
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['rest_namespace'] = $this->value;
    }
} 