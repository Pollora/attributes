<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the description parameter for a post type.
 *
 * The description parameter provides a short explanation of what the post type is for.
 * This description is shown in the admin interface.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Description extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  string  $value  The description of the post type
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the post type with the description parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['description'] = $this->value;
    }
}
