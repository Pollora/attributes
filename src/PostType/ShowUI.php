<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the show_ui parameter for a post type.
 *
 * When set to true, the post type will have a default UI in the admin panel.
 * When set to false, no UI is generated for the post type.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowUI extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type should have a UI
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the show_ui parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['show_ui'] = $this->value;
    }
}
