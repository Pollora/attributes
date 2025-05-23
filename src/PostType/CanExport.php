<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the can_export parameter for a post type.
 *
 * When set to true, the post type can be exported using WordPress's built-in export tools.
 * When set to false, the post type will not be included in exports.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class CanExport extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type can be exported
     */
    public function __construct(
        private readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the can_export parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['can_export'] = $this->value;
    }
}
