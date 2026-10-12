<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the featured_image parameter for a post type.
 *
 * Sets a custom featured image label.
 * This is a feature provided by the Extended CPTs library.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class FeaturedImage implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The custom featured image label
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the post type with the featured_image parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['featured_image'] = $this->value;

        return $arguments;
    }
}
