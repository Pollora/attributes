<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the publicly_queryable parameter for a post type.
 *
 * When set to true, the post type will be queryable via the front end.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class PubliclyQueryable implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type should be publicly queryable
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the publicly_queryable parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['publicly_queryable'] = $this->value;

        return $arguments;
    }
}
