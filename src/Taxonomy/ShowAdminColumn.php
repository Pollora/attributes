<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set whether to display a column for the taxonomy on its post type listing screens.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowAdminColumn extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to display a column for the taxonomy
     */
    public function __construct(
        private readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the show_admin_column parameter.
     *
     * @param  Taxonomy  $taxonomy  The taxonomy to configure
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['show_admin_column'] = $this->value;
    }
}
