<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

/**
 * Attribute to set the rewrite rules for the taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Rewrite extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool|array  $value  The rewrite rules for the taxonomy
     */
    public function __construct(
        private readonly bool|array $value = true
    ) {}

    /**
     * Configure the taxonomy with the rewrite parameter.
     *
     * @param  TaxonomyAttributeInterface  $taxonomy  The taxonomy to configure
     */
    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $taxonomy->attributeArgs['rewrite'] = $this->value;
    }
}
