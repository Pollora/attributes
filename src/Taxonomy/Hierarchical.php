<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set whether the taxonomy is hierarchical.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Hierarchical extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the taxonomy is hierarchical
     */
    public function __construct(
        private bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the hierarchical parameter.
     *
     * @param  Taxonomy  $taxonomy  The taxonomy to configure
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['hierarchical'] = $this->value;
    }
}
