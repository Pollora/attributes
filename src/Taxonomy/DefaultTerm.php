<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set the default term name for this taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class DefaultTerm extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  string|array  $value  The default term name or an array with 'name', 'slug', and 'description'
     */
    public function __construct(
        private readonly string|array $value
    ) {}

    /**
     * Configure the taxonomy with the default_term parameter.
     *
     * @param  Taxonomy  $taxonomy  The taxonomy to configure
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['default_term'] = $this->value;
    }
}
