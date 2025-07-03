<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the archive parameter for a post type.
 *
 * An array of archive display options.
 * This is a feature provided by the Extended CPTs library.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Archive extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of archive display options
     */
    public function __construct(
        public readonly array $value
    ) {}

    /**
     * Configure the post type with the archive parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['archive'] = $this->value;
    }
}
