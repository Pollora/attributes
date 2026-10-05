<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;

/**
 * Role Attribute
 *
 * Declares a WordPress role in code. Pollora injects it into WordPress on each
 * request instead of writing it to the database, so the code stays the only
 * source of truth: change the class, deploy, and the role follows.
 *
 *     #[Role('event_manager', label: 'Event manager', inherits: 'author')]
 *     #[GrantsPostType(Event::class, Access::Editor)]
 *     #[Grants(EventCap::ExportAttendees)]
 *     #[Without('publish_posts')]
 *     final class EventManager {}
 *
 * A core role (`administrator`, `editor`…) cannot be redeclared: adjust it with
 * `#[ModifyRole]`.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Role
{
    /**
     * @param  string  $slug  The role slug, as stored on users
     * @param  string|null  $label  The name shown in the admin. Defaults to the class name, humanized
     * @param  string|null  $inherits  A role whose capabilities are the starting point: a slug, or the class of a #[Role]
     * @param  bool  $allowSensitive  Required to grant a sensitive capability (manage_options, edit_users…)
     */
    public function __construct(
        public string $slug,
        public ?string $label = null,
        public ?string $inherits = null,
        public bool $allowSensitive = false,
    ) {}
}
