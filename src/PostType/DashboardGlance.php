<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the dashboard_glance parameter for a post type.
 *
 * Shows the post type in the "At a Glance" widget on the WordPress dashboard.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class DashboardGlance extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to show the post type in the dashboard glance widget
     */
    public function __construct(
        private bool $value = true
    ) {}

    /**
     * Configure the post type with the dashboard_glance parameter.
     *
     * @param  PostType  $postType  The post type to configure
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['dashboard_glance'] = $this->value;
    }
}
