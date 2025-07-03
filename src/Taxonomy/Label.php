<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

/**
 * Attribute to set the singular label for a taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Label extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  string  $value  The singular label for the taxonomy
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the taxonomy with the label parameter.
     *
     * @param  TaxonomyAttributeInterface  $taxonomy  The taxonomy to configure
     */
    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $taxonomy->attributeArgs['label'] = $this->value;
    }
}
