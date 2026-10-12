<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the label parameter for a post type.
 *
 * The label parameter is a general name for the post type, usually plural.
 * This is used in various places in the WordPress admin.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Label implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The general name for the post type, usually plural
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the post type with the label parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['label'] = $this->value;

        return $arguments;
    }
}
