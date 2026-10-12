<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set whether to display a column for the taxonomy on its post type listing screens.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowAdminColumn implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to display a column for the taxonomy
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the show_admin_column parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['show_admin_column'] = $this->value;

        return $arguments;
    }
}
