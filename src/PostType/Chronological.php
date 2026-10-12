<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the chronological parameter for a post type.
 *
 * Sets the post type to be displayed in chronological order (newest first)
 * in admin lists and queries. This is a convenience attribute that sets
 * the default ordering to date, in descending order.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Chronological implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to display the post type chronologically
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the chronological parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        if ($this->value) {
            // Set default ordering to date, descending
            $arguments['orderby'] = 'date';
            $arguments['order'] = 'DESC';
        }

        return $arguments;
    }
}
