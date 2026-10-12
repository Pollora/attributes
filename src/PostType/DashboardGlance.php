<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the dashboard_glance parameter for a post type.
 *
 * Shows the post type in the "At a Glance" widget on the WordPress dashboard.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class DashboardGlance implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to show the post type in the dashboard glance widget
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the dashboard_glance parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['dashboard_glance'] = $this->value;

        return $arguments;
    }
}
