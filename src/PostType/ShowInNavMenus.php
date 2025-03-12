<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the show_in_nav_menus parameter for a post type.
 *
 * When set to true, the post type is available for selection in navigation menus.
 * When set to false, the post type is not available in navigation menus.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInNavMenus extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param bool $value Whether the post type should be available in navigation menus
     */
    public function __construct(
        private bool $value = true
    ) {
    }

    /**
     * Configure the post type with the show_in_nav_menus parameter.
     *
     * @param PostType $postType The post type to configure
     *
     * @return void
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['show_in_nav_menus'] = $this->value;
    }
} 