<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the show_ui parameter for a post type.
 *
 * When set to true, the post type will have a default UI in the admin panel.
 * When set to false, no UI is generated for the post type.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowUI implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type should have a UI
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the show_ui parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['show_ui'] = $this->value;

        return $arguments;
    }
}
