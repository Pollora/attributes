<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the admin_cols parameter for a post type.
 *
 * Configures the columns displayed in the admin list table for this post type.
 * This is a feature provided by the Extended CPTs library.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AdminCols implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of admin columns configuration
     */
    public function __construct(
        public readonly array $value
    ) {}

    /**
     * Configure the post type with the admin_cols parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['admin_cols'] = $this->value;

        return $arguments;
    }
}
