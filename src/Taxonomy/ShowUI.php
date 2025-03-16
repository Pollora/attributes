<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set whether to generate a default UI for managing this taxonomy in the admin.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowUI extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to generate a default UI for managing this taxonomy in the admin
     */
    public function __construct(
        private readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the show_ui parameter.
     *
     * @param  Taxonomy  $taxonomy  The taxonomy to configure
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['show_ui'] = $this->value;
    }
}
