<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

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
class ShowInMenu implements ConfiguresArguments
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
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['show_in_menu'] = $this->value;

        return $arguments;
    }
}
