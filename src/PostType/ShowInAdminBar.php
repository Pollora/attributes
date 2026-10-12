<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the show_in_admin_bar parameter for a post type.
 *
 * When set to true, the post type will be shown in the WordPress admin bar.
 * When set to false, the post type will not be shown in the admin bar.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInAdminBar implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type should be shown in the admin bar
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the show_in_admin_bar parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['show_in_admin_bar'] = $this->value;

        return $arguments;
    }
}
