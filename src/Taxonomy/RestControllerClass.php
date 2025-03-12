<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set the controller class that should be used for handling REST API requests for this taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class RestControllerClass extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  string  $value  The controller class for REST API requests
     */
    public function __construct(
        private string $value
    ) {}

    /**
     * Configure the taxonomy with the rest_controller_class parameter.
     *
     * @param  Taxonomy  $taxonomy  The taxonomy to configure
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['rest_controller_class'] = $this->value;
    }
}
