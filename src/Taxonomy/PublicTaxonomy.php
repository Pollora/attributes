<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the public parameter for a taxonomy.
 *
 * When set to true, the taxonomy will be publicly accessible.
 * When set to false, the taxonomy will be private.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class PublicTaxonomy implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the taxonomy should be public
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the public parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['public'] = $this->value;

        return $arguments;
    }
}
