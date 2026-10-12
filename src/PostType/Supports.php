<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the supports parameter for a post type.
 *
 * Defines which features the post type supports (title, editor, comments, etc.).
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Supports implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  array<string>  $features  Array of features the post type supports
     */
    public function __construct(
        public readonly array $features = ['title', 'editor']
    ) {}

    /**
     * Configure the post type with the supports parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['supports'] = $this->features;

        return $arguments;
    }
}
