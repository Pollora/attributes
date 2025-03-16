<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set the query_var key for this taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class QueryVar extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool|string  $value  The query_var key for this taxonomy
     */
    public function __construct(
        private readonly bool|string $value = true
    ) {}

    /**
     * Configure the taxonomy with the query_var parameter.
     *
     * @param  Taxonomy  $taxonomy  The taxonomy to configure
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['query_var'] = $this->value;
    }
}
