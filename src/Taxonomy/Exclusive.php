<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

/**
 * Attribute to set whether the taxonomy should be exclusive.
 *
 * This sets the meta box to the 'radio' meta box, thus meaning any given post can only have one term
 * associated with it for that taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Exclusive extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the taxonomy should be exclusive
     */
    public function __construct(
        private readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the exclusive parameter.
     *
     * @param  TaxonomyAttributeInterface  $taxonomy  The taxonomy to configure
     */
    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $taxonomy->attributeArgs['exclusive'] = $this->value;
    }
}
