<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set arguments to automatically use inside wp_get_object_terms() for this taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Args implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  array  $value  Arguments to use inside wp_get_object_terms()
     */
    public function __construct(
        public readonly array $value
    ) {}

    /**
     * Configure the taxonomy with the args parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['args'] = $this->value;

        return $arguments;
    }
}
