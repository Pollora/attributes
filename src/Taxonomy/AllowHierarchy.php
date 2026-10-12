<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set whether to allow hierarchy in the taxonomy's rewrite rules.
 *
 * All this does currently is disable hierarchy in the taxonomy's rewrite rules.
 * Default false.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AllowHierarchy implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether to allow hierarchy in the taxonomy's rewrite rules
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the allow_hierarchy parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['allow_hierarchy'] = $this->value;

        return $arguments;
    }
}
