<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the show_in_menu parameter for a post type.
 *
 * Controls where the post type appears in the admin menu.
 * - true: Shows in the main menu
 * - false: Does not show in the menu
 * - string: Shows as a submenu of the specified top-level menu
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInMenu extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool|string  $value  Where to show the post type in the admin menu
     */
    public function __construct(
        public readonly bool|string $value = true
    ) {}

    /**
     * Configure the post type with the show_in_menu parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['show_in_menu'] = $this->value;
    }
}
