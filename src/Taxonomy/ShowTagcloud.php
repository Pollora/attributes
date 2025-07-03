<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

/**
 * Attribute to set whether to show the taxonomy in the tag cloud widget.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowTagcloud extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to show the taxonomy in the tag cloud widget
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the show_tagcloud parameter.
     *
     * @param  TaxonomyAttributeInterface  $taxonomy  The taxonomy to configure
     */
    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $taxonomy->attributeArgs['show_tagcloud'] = $this->value;
    }
}
