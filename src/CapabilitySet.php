<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;

/**
 * CapabilitySet Attribute
 *
 * Declares the project's own capabilities as a backed enum. They can then be
 * granted as enum cases (`#[Grants(EventCap::ExportAttendees)]`), checked with
 * the Gate, and the roles listed in `roles.super_roles` receive them all:
 *
 *     #[CapabilitySet(label: 'Events')]
 *     enum EventCap: string
 *     {
 *         case ExportAttendees = 'export_attendees';
 *     }
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class CapabilitySet
{
    public function __construct(
        public ?string $label = null,
    ) {}
}
