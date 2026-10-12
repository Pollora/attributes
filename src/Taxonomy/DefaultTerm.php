<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the default term name for this taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class DefaultTerm implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string|array  $value  The default term name or an array with 'name', 'slug', and 'description'
     */
    public function __construct(
        public readonly string|array $value
    ) {}

    /**
     * Configure the taxonomy with the default_term parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['default_term'] = $this->value;

        return $arguments;
    }
}
