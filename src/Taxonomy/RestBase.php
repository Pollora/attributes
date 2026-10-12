<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the base URL segment that will be used in REST API endpoints for this taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class RestBase implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The base URL segment for REST API endpoints
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the taxonomy with the rest_base parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['rest_base'] = $this->value;

        return $arguments;
    }
}
