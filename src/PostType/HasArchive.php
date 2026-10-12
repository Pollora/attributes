<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the has_archive parameter for a post type.
 *
 * When set to true, the post type will have an archive page.
 * Can also be set to a string to customize the archive slug.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class HasArchive implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool|string  $value  Whether the post type should have an archive or the archive slug
     */
    public function __construct(
        public readonly bool|string $value = true
    ) {}

    /**
     * Configure the post type with the has_archive parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['has_archive'] = $this->value;

        return $arguments;
    }
}
