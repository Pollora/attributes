<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set whether this taxonomy is available for selection in navigation menus.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInNavMenus implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether this taxonomy is available for selection in navigation menus
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the show_in_nav_menus parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['show_in_nav_menus'] = $this->value;

        return $arguments;
    }
}
