<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;

/**
 * Declares `#[Meta]` properties for comments, WooCommerce reviews included.
 *
 *     #[CommentMeta]
 *     class ReviewMeta
 *     {
 *         #[Meta]
 *         public bool $verifiedPurchase = false;
 *     }
 *
 * The meta apply to every comment type: WordPress registers comment meta for
 * all comments, it has no per-type registration.
 *
 * @experimental The API may still change before it is declared stable.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class CommentMeta {}
