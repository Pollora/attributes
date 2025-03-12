<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set whether terms in this taxonomy should be sorted in the order they are provided.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Sort extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether terms should be sorted
     */
    public function __construct(
        private bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the sort parameter.
     *
     * @param  Taxonomy  $taxonomy  The taxonomy to configure
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['sort'] = $this->value;
    }
}
