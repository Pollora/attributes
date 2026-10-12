<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the REST API namespace for the taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class RestNamespace implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The REST API namespace for the taxonomy
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the taxonomy with the rest_namespace parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['rest_namespace'] = $this->value;

        return $arguments;
    }
}
