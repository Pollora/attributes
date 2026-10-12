<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the query_var parameter for a post type.
 *
 * Sets the query_var key for this post type.
 * - true: Sets to the post type's name
 * - false: Disables query_var
 * - string: Sets a custom query_var key
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class QueryVar implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool|string  $value  The query_var value for the post type
     */
    public function __construct(
        public readonly bool|string $value = true
    ) {}

    /**
     * Configure the post type with the query_var parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['query_var'] = $this->value;

        return $arguments;
    }
}
