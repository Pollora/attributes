<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

/**
 * Attribute to set the post types this taxonomy is associated with.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ObjectType extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  string|array<string>  $value  The post type(s) this taxonomy is associated with
     */
    public function __construct(
        private readonly string|array $value
    ) {}

    /**
     * Configure the taxonomy with the object_type parameter.
     *
     * @param  TaxonomyAttributeInterface  $taxonomy  The taxonomy to configure
     */
    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $taxonomy->attributeArgs['object_type'] = $this->value;
    }
}
