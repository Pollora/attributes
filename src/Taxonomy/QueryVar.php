<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

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
        public readonly bool|string $value = true
    ) {}

    /**
     * Configure the taxonomy with the query_var parameter.
     *
     * @param  TaxonomyAttributeInterface  $taxonomy  The taxonomy to configure
     */
    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $taxonomy->attributeArgs['query_var'] = $this->value;
    }
}
