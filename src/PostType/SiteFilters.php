<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the site_filters parameter for a post type.
 *
 * An array of taxonomy filters for front-end filtering.
 * This is a feature provided by the Extended CPTs library.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class SiteFilters implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of site filters
     */
    public function __construct(
        public readonly array $value
    ) {}

    /**
     * Configure the post type with the site_filters parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['site_filters'] = $this->value;

        return $arguments;
    }
}
