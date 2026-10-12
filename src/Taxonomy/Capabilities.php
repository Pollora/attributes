<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the capabilities parameter for a taxonomy.
 *
 * Provides an array of capabilities for this taxonomy.
 * See WordPress documentation for the full list of taxonomy capabilities.
 *
 * Available capabilities:
 * - manage_terms: capability required to access the taxonomy management screen
 * - edit_terms: capability required to edit terms in the taxonomy
 * - delete_terms: capability required to delete terms from the taxonomy
 * - assign_terms: capability required to assign terms in the taxonomy to posts
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Capabilities implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  array  $value  The capabilities array for the taxonomy
     */
    public function __construct(
        public readonly array $value
    ) {}

    /**
     * Configure the taxonomy with the capabilities parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['capabilities'] = $this->value;

        return $arguments;
    }
}
