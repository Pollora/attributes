<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Contracts\Taxonomy;

/**
 * Attribute to set whether to allow hierarchy in the taxonomy's rewrite rules.
 *
 * All this does currently is disable hierarchy in the taxonomy's rewrite rules.
 * Default false.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AllowHierarchy extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to allow hierarchy in the taxonomy's rewrite rules
     */
    public function __construct(
        private readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the allow_hierarchy parameter.
     *
     * @param  Taxonomy  $taxonomy  The taxonomy to configure
     */
    protected function configure(Taxonomy $taxonomy): void
    {
        $taxonomy->attributeArgs['allow_hierarchy'] = $this->value;
    }
}
