<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the title_placeholder parameter for a post type.
 *
 * This attribute customizes the placeholder text in the title field
 * when creating a new post of this type.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class TitlePlaceholder implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The placeholder text for the title field
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the post type with the title_placeholder parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['title_placeholder'] = $this->value;

        return $arguments;
    }
}
