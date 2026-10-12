<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set whether to show the taxonomy in the quick/bulk edit panel.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInQuickEdit implements ConfiguresArguments
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
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['show_in_quick_edit'] = $this->value;

        return $arguments;
    }
}
