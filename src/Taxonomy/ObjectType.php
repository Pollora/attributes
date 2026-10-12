<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the post types this taxonomy is associated with.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ObjectType implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  string|array<string>  $value  The post type(s) this taxonomy is associated with
     */
    public function __construct(
        public readonly string|array $value
    ) {}

    /**
     * Configure the taxonomy with the object_type parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['object_type'] = $this->value;

        return $arguments;
    }
}
