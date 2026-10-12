<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the controller class that should be used for handling REST API requests for this taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class RestControllerClass implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The controller class for REST API requests
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the taxonomy with the rest_controller_class parameter.
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
