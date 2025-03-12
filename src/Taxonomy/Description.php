<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set the description for a taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Description extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param string $value The description for the taxonomy
     */
    public function __construct(
        private string $value
    ) {
    }

    /**
     * Configure the taxonomy with the description parameter.
     *
     * @param Taxonomy $taxonomy The taxonomy to configure
     *
     * @return void
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['description'] = $this->value;
    }
} 