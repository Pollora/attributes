<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set whether this taxonomy is available for selection in navigation menus.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInNavMenus extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param bool $value Whether this taxonomy is available for selection in navigation menus
     */
    public function __construct(
        private bool $value = true
    ) {
    }

    /**
     * Configure the taxonomy with the show_in_nav_menus parameter.
     *
     * @param Taxonomy $taxonomy The taxonomy to configure
     *
     * @return void
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['show_in_nav_menus'] = $this->value;
    }
} 