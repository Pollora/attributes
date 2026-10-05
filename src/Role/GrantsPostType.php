<?php

declare(strict_types=1);

namespace Pollora\Attributes\Role;

use Attribute;
use Pollora\Role\Domain\Enums\Access;

/**
 * Grants the capabilities of a post type at an access level.
 *
 * The post type needs its own capabilities (`#[CapabilityType]`); their names
 * are computed the way WordPress does (`edit_events`, `publish_events`…).
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class GrantsPostType
{
    /**
     * @param  string  $postType  The class of a #[PostType], or a post type slug
     * @param  Access  $access  Contributor, Author or Editor; each level includes the previous ones
     */
    public function __construct(
        public string $postType,
        public Access $access = Access::Editor,
    ) {}
}
