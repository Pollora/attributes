<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the rest_controller_class parameter for a post type.
 *
 * Sets the controller class name for the REST API.
 * By default, this is 'WP_REST_Posts_Controller'.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class RestControllerClass implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The REST controller class for the post type
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the post type with the rest_controller_class parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['rest_controller_class'] = $this->value;

        return $arguments;
    }
}
