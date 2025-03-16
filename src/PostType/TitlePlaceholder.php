<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

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
        private readonly string $value
    ) {}

    /**
     * Configure the post type with the title_placeholder parameter.
     *
     * @param  PostType  $postType  The post type to configure
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['title_placeholder'] = $this->value;
    }
}
