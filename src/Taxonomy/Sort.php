<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set whether terms in this taxonomy should be sorted in the order they are provided.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Sort implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether terms should be sorted
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the sort parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['sort'] = $this->value;

        return $arguments;
    }
}
