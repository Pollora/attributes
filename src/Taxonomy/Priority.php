<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the priority a post type declaration.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Priority implements ConfiguresArguments
{
    /**
     * Constructor.
     */
    public function __construct(
        public readonly int $priority = 5
    ) {}

    /**
     * Configure the post type priority declaration parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['priority'] = $this->priority;

        return $arguments;
    }
}
