<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the description for a taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Description implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The description for the taxonomy
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the taxonomy with the description parameter.
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
