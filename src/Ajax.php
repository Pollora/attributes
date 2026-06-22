<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Pollora\Ajax\Infrastructure\Services\AjaxDiscovery;

/**
 * Attribute for declarative WordPress AJAX action registration.
 *
 * Place this attribute on a public method to register it as a WordPress AJAX
 * handler. The method is automatically discovered and wired via the
 * {@see AjaxDiscovery} system.
 *
 * By default, the action is restricted to **logged-in users** (security-by-default).
 * Use the `access` parameter to change targeting.
 *
 * Usage:
 *
 *     #[Ajax('subscribe')]                              // logged-in only (default)
 *     #[Ajax('load_more', access: AjaxAccess::ALL)]     // all users
 *     #[Ajax('track_visit', access: AjaxAccess::GUEST)] // guests only
 *
 * @see AjaxAccess
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Ajax
{
    /**
     * @param  string  $action  The WordPress AJAX action name (used in `wp_ajax_{action}` hooks).
     * @param  AjaxAccess  $access  The audience targeting for this action. Defaults to logged-in users only.
     */
    public function __construct(
        public readonly string $action,
        public readonly AjaxAccess $access = AjaxAccess::LOGGED,
    ) {}
}
