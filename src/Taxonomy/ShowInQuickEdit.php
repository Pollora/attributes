<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

/**
 * Attribute to set whether to show the taxonomy in the quick/bulk edit panel.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInQuickEdit extends TaxonomyAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to show the taxonomy in the quick/bulk edit panel
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the show_in_quick_edit parameter.
     *
     * @param  TaxonomyAttributeInterface  $taxonomy  The taxonomy to configure
     */
    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $taxonomy->attributeArgs['show_in_quick_edit'] = $this->value;
    }
}
