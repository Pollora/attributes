<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set whether the taxonomy should be exclusive.
 *
 * This sets the meta box to the 'radio' meta box, thus meaning any given post can only have one term
 * associated with it for that taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Exclusive implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the taxonomy should be exclusive
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the exclusive parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['exclusive'] = $this->value;

        return $arguments;
    }
}
