<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the rest_namespace parameter for a post type.
 *
 * Sets the namespace for the REST API endpoints for this post type.
 * By default, this is 'wp/v2'.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class RestNamespace implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The REST API namespace for the post type
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the post type with the rest_namespace parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['rest_namespace'] = $this->value;

        return $arguments;
    }
}
