<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set whether this taxonomy should appear in the REST API.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ShowInRest implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether this taxonomy should appear in the REST API
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the show_in_rest parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['show_in_rest'] = $this->value;

        return $arguments;
    }
}
