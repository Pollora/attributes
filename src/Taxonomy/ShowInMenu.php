<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

/**
 * Attribute to set the show_in_menu parameter for a taxonomy.
 *
 * Controls where the taxonomy appears in the admin menu.
 * - true: Shows in the main menu
 * - false: Does not show in the menu
 * - string: Shows as a submenu of the specified top-level menu
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInMenu extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool|string  $value  Where to show the taxonomy in the admin menu
     */
    public function __construct(
        public readonly bool|string $value = true
    ) {}

    /**
     * Configure the taxonomy with the show_in_menu parameter.
     *
     * @param  TaxonomyAttributeInterface  $taxonomy  The taxonomy to configure
     */
    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $taxonomy->attributeArgs['show_in_menu'] = $this->value;
    }
}
