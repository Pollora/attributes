<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the show_in_admin_bar parameter for a post type.
 *
 * When set to true, the post type will be shown in the WordPress admin bar.
 * When set to false, the post type will not be shown in the admin bar.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInAdminBar extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type should be shown in the admin bar
     */
    public function __construct(
        private bool $value = true
    ) {}

    /**
     * Configure the post type with the show_in_admin_bar parameter.
     *
     * @param  PostType  $postType  The post type to configure
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['show_in_admin_bar'] = $this->value;
    }
}
