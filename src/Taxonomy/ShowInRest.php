<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set whether this taxonomy should appear in the REST API.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInRest extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether this taxonomy should appear in the REST API
     */
    public function __construct(
        private bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the show_in_rest parameter.
     *
     * @param  Taxonomy  $taxonomy  The taxonomy to configure
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['show_in_rest'] = $this->value;
    }
}
