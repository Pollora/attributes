<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the menu_position parameter for a post type.
 *
 * The menu_position parameter determines where the post type should appear in the admin menu.
 * Common positions:
 * - 5 - below Posts
 * - 10 - below Media
 * - 15 - below Links
 * - 20 - below Pages
 * - 25 - below Comments
 * - 60 - below first separator
 * - 65 - below Plugins
 * - 70 - below Users
 * - 75 - below Tools
 * - 80 - below Settings
 * - 100 - below second separator
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class MenuPosition extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  int  $value  The position in the menu order the post type should appear
     */
    public function __construct(
        private readonly int $value
    ) {}

    /**
     * Configure the post type with the menu_position parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['menu_position'] = $this->value;
    }
}
