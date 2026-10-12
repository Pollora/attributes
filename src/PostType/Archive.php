<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the archive parameter for a post type.
 *
 * An array of archive display options.
 * This is a feature provided by the Extended CPTs library.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Archive implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  array  $value  An array of archive display options
     */
    public function __construct(
        public readonly array $value
    ) {}

    /**
     * Configure the post type with the archive parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['archive'] = $this->value;

        return $arguments;
    }
}
