<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the title_placeholder parameter for a post type.
 *
 * This attribute customizes the placeholder text in the title field
 * when creating a new post of this type.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class TitlePlaceholder extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  string  $value  The placeholder text for the title field
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the post type with the title_placeholder parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['title_placeholder'] = $this->value;
    }
}
