<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the description parameter for a post type.
 *
 * The description parameter provides a short explanation of what the post type is for.
 * This description is shown in the admin interface.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Description implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The description of the post type
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the post type with the description parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['description'] = $this->value;

        return $arguments;
    }
}
