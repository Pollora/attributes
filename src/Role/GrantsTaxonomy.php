<?php

declare(strict_types=1);

namespace Pollora\Attributes\Role;

use Attribute;

/**
 * Grants the term capabilities (manage, edit, delete, assign) of a taxonomy.
 *
 * The taxonomy needs its own capabilities (`#[Taxonomy\Capabilities]`):
 * otherwise it shares `manage_categories` with the core categories.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class GrantsTaxonomy
{
    /**
     * @param  string  $taxonomy  The class of a #[Taxonomy], or a taxonomy slug
     */
    public function __construct(
        public string $taxonomy,
    ) {}
}
