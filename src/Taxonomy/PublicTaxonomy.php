<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set the public parameter for a taxonomy.
 *
 * When set to true, the taxonomy will be publicly accessible.
 * When set to false, the taxonomy will be private.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class PublicTaxonomy extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the taxonomy should be public
     */
    public function __construct(
        private bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the public parameter.
     *
     * @param  Taxonomy  $taxonomy  The taxonomy to configure
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['public'] = $this->value;
    }
}
