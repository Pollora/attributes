<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the template parameter for a post type.
 *
 * An array of blocks to use as the default initial state for a post.
 * Each item is an array containing block name and optional attributes.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Template implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of blocks to use as the default template
     */
    public function __construct(
        public readonly array $value
    ) {}

    /**
     * Configure the post type with the template parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['template'] = $this->value;

        return $arguments;
    }
}
