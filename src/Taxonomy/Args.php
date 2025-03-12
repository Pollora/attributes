<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set arguments to automatically use inside wp_get_object_terms() for this taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Args extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  array  $value  Arguments to use inside wp_get_object_terms()
     */
    public function __construct(
        private array $value
    ) {}

    /**
     * Configure the taxonomy with the args parameter.
     *
     * @param  Taxonomy  $taxonomy  The taxonomy to configure
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['args'] = $this->value;
    }
}
