<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

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
     * @param  bool  $value  Whether to show this post type in the dashboard activity widget
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the dashboard_activity parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['dashboard_activity'] = $this->value;
    }
}
