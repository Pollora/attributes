<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

/**
 * Attribute to set the priority a post type declaration.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Priority extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  init  $priority  The taxonomy priority declaration
     */
    public function __construct(
        public readonly int $priority = 5
    ) {}

    /**
     * Configure the post type priority declaration parameter.
     *
     * @param  TaxonomyAttributeInterface  $taxonomy  The post type to configure
     */
    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $taxonomy->attributeArgs['priority'] = $this->priority;
    }
}
