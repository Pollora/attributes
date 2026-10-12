<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the query_var key for this taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class QueryVar implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool|string  $value  The query_var key for this taxonomy
     */
    public function __construct(
        public readonly bool|string $value = true
    ) {}

    /**
     * Configure the taxonomy with the query_var parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['query_var'] = $this->value;

        return $arguments;
    }
}
