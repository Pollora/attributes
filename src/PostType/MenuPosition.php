<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

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
class MenuPosition implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  int  $value  The position in the menu order the post type should appear
     */
    public function __construct(
        public readonly int $value
    ) {}

    /**
     * Configure the post type with the menu_position parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['menu_position'] = $this->value;

        return $arguments;
    }
}
