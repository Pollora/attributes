<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the menu_icon parameter for a post type.
 *
 * The menu_icon parameter sets the dashicon to use for the post type in the admin menu.
 * You can use any WordPress Dashicon or a URL to a custom icon.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class MenuIcon implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The dashicon name or URL to use as the menu icon
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the post type with the menu_icon parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['menu_icon'] = $this->value;

        return $arguments;
    }
}
