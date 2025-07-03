<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

/**
 * Attribute to set the REST API namespace for the taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class RestNamespace extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  string  $value  The REST API namespace for the taxonomy
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the taxonomy with the rest_namespace parameter.
     *
     * @param  TaxonomyAttributeInterface  $taxonomy  The taxonomy to configure
     */
    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $taxonomy->attributeArgs['rest_namespace'] = $this->value;
    }
}
