<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the rewrite parameter for a post type.
 *
 * Controls the permalink structure for the post type.
 * - true: Uses the post type name as the slug
 * - false: Disables permalinks for this post type
 * - array: Custom rewrite rules (see WordPress documentation)
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Rewrite implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool|array  $value  The rewrite configuration for the post type
     */
    public function __construct(
        public readonly bool|array $value = true
    ) {}

    /**
     * Configure the post type with the rewrite parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['rewrite'] = $this->value;

        return $arguments;
    }
}
