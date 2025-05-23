<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the rest_base parameter for a post type.
 *
 * The rest_base parameter defines the base URL segment that will be used in REST API
 * endpoints for this post type. If not specified, the post type slug will be used.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class RestBase extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  string  $value  The base URL segment for REST API endpoints
     */
    public function __construct(
        private readonly string $value
    ) {}

    /**
     * Configure the post type with the rest_base parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['rest_base'] = $this->value;
    }
}
