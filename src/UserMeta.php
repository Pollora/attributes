<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;

/**
 * Declares `#[Meta]` properties for users. Several classes may declare user
 * meta, one per module for instance, as long as their keys differ.
 *
 *     #[UserMeta]
 *     class MemberProfile
 *     {
 *         #[Meta(showInRest: true)]
 *         public bool $newsletterOptIn = false;
 *     }
 *
 *     Meta::of(MemberProfile::class, $userId)->newsletterOptIn;
 *
 * @experimental The API may still change before it is declared stable.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class UserMeta {}
