<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

/**
 * Attribute to set the label parameter for a taxonomy.
 *
 * The label parameter is a general name for the taxonomy, usually plural.
 * This is used in various places in the WordPress admin.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Labels extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  string  $value  The general name for the taxonomy, usually plural
     */
    public function __construct(
        public readonly array $value
    ) {}

    /**
     * Configure the taxonomy with the label parameter.
     *
     * @param  TaxonomyAttributeInterface  $taxonomy  The taxonomy to configure
     */
    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $taxonomy->attributeArgs['labels'] = $this->value;
    }
}
