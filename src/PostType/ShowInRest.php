<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the show_in_rest parameter for a post type.
 *
 * When set to true, the post type will be available via the REST API.
 * This is needed for the Gutenberg editor to work with the post type.
 * When set to false, the post type will not be available via the REST API.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInRest extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type should be available in the REST API
     */
    public function __construct(
        private readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the show_in_rest parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['show_in_rest'] = $this->value;
    }
}
