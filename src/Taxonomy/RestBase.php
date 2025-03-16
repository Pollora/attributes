<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set the base URL segment that will be used in REST API endpoints for this taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class RestBase extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  string  $value  The base URL segment for REST API endpoints
     */
    public function __construct(
        private readonly string $value
    ) {}

    /**
     * Configure the taxonomy with the rest_base parameter.
     *
     * @param  Taxonomy  $taxonomy  The taxonomy to configure
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['rest_base'] = $this->value;
    }
}
