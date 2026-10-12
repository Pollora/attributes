<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the dashboard_activity parameter for a post type.
 *
 * When set to true, recent activity for this post type will be shown in the
 * WordPress dashboard "Activity" widget.
 * When set to false, this post type will not appear in the dashboard activity.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class DashboardActivity implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to show this post type in the dashboard activity widget
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the dashboard_activity parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['dashboard_activity'] = $this->value;

        return $arguments;
    }
}
