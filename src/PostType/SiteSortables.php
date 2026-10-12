<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the site_sortables parameter for a post type.
 *
 * An array of sortable columns for front-end sorting.
 * This is a feature provided by the Extended CPTs library.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class SiteSortables implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of site sortables
     */
    public function __construct(
        public readonly array $value
    ) {}

    /**
     * Configure the post type with the site_sortables parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['site_sortables'] = $this->value;

        return $arguments;
    }
}
