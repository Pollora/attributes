<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the taxonomies parameter for a post type.
 *
 * An array of taxonomy names that will be registered for the post type.
 * This is used to connect the post type to existing taxonomies.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Taxonomies implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of taxonomy names
     */
    public function __construct(
        public readonly array $value
    ) {}

    /**
     * Configure the post type with the taxonomies parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['taxonomies'] = $this->value;

        return $arguments;
    }
}
