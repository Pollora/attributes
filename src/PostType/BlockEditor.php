<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the block_editor parameter for a post type.
 *
 * When set to true, the Gutenberg block editor will be used for this post type.
 * When set to false, the classic editor will be used instead.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class BlockEditor implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to use the block editor for this post type
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the block_editor parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['show_in_rest'] = $this->value;

        return $arguments;
    }
}
