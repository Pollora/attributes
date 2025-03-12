<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

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
class DashboardActivity extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param bool $value Whether to show this post type in the dashboard activity widget
     */
    public function __construct(
        private bool $value = true
    ) {
    }

    /**
     * Configure the post type with the dashboard_activity parameter.
     *
     * @param PostType $postType The post type to configure
     *
     * @return void
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['dashboard_activity'] = $this->value;
    }
} 