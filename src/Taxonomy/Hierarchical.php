<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set whether the taxonomy is hierarchical.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Hierarchical implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the taxonomy is hierarchical
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the hierarchical parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['hierarchical'] = $this->value;

        return $arguments;
    }
}
