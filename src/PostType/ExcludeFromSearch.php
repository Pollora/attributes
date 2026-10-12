<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the exclude_from_search parameter for a post type.
 *
 * When set to true, the post type will be excluded from search results.
 * When set to false, the post type will be included in search results.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ExcludeFromSearch implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type should be excluded from search results
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the exclude_from_search parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['exclude_from_search'] = $this->value;

        return $arguments;
    }
}
