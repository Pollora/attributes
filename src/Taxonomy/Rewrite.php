<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the rewrite rules for the taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Rewrite implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool|array  $value  The rewrite rules for the taxonomy
     */
    public function __construct(
        public readonly bool|array $value = true
    ) {}

    /**
     * Configure the taxonomy with the rewrite parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['rewrite'] = $this->value;

        return $arguments;
    }
}
