<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the can_export parameter for a post type.
 *
 * When set to true, the post type can be exported using WordPress's built-in export tools.
 * When set to false, the post type will not be included in exports.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class CanExport implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type can be exported
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the can_export parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['can_export'] = $this->value;

        return $arguments;
    }
}
