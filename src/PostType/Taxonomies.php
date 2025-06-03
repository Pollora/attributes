<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the taxonomies parameter for a post type.
 *
 * An array of taxonomy names that will be registered for the post type.
 * This is used to connect the post type to existing taxonomies.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Taxonomies extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of taxonomy names
     */
    public function __construct(
        private readonly array $value
    ) {}

    /**
     * Configure the post type with the taxonomies parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['taxonomies'] = $this->value;
    }
}
