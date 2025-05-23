<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

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
     * @param  string  $value  The description for the taxonomy
     */
    public function __construct(
        private readonly string $value
    ) {}

    /**
     * Configure the taxonomy with the description parameter.
     *
     * @param  TaxonomyAttributeInterface  $taxonomy  The taxonomy to configure
     */
    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $taxonomy->attributeArgs['description'] = $this->value;
    }
}
