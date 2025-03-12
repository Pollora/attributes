<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the menu_icon parameter for a post type.
 *
 * The menu_icon parameter sets the dashicon to use for the post type in the admin menu.
 * You can use any WordPress Dashicon or a URL to a custom icon.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class MenuIcon extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param string $value The dashicon name or URL to use as the menu icon
     */
    public function __construct(
        private string $value
    ) {
    }

    /**
     * Configure the post type with the menu_icon parameter.
     *
     * @param PostType $postType The post type to configure
     *
     * @return void
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['menu_icon'] = $this->value;
    }
} 