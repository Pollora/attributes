<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the singular label for a taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Label implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string  $value  The singular label for the taxonomy
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the taxonomy with the label parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['label'] = $this->value;

        return $arguments;
    }
}
