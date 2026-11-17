<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Pollora\Ajax\Domain\Model\AjaxAccess;

/**
 * Attribute for declarative WordPress AJAX action registration.
 *
 * Place this attribute on a public method to register it as a WordPress AJAX
 * handler. The method is discovered and wired automatically.
 *
 * By default, the action is restricted to **logged-in users** (security-by-default).
 * Use the `access` parameter to change targeting.
 *
 * The nonce says how the action is protected against cross-site request forgery:
 * `true` verifies a nonce named after the action, a string verifies a nonce of
 * that name, `false` turns it off on purpose. An action that does not say runs
 * without a check for now and is reported; the next major version of
 * pollora/ajax verifies a nonce by default.
 *
 * Usage:
 *
 *     #[Ajax('subscribe')]                              // logged-in only (default), nonce not chosen yet
 *     #[Ajax('save_note', nonce: true)]                 // logged-in only, verifies the nonce `save_note`
 *     #[Ajax('save_note', nonce: 'notes_form')]         // verifies the nonce `notes_form`
 *     #[Ajax('load_more', access: AjaxAccess::ALL)]     // all users
 *     #[Ajax('track_visit', access: AjaxAccess::GUEST, nonce: false)] // guests only, no nonce on purpose
 *
 * @see AjaxAccess
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Ajax
{
    /**
     * @param  string  $action  The WordPress AJAX action name (used in `wp_ajax_{action}` hooks).
     * @param  AjaxAccess  $access  The audience targeting for this action. Defaults to logged-in users only.
     * @param  bool|string|null  $nonce  `true` verifies a nonce named after the action, a string verifies the nonce of
     *                                   that name, `false` turns the nonce off on purpose, `null` (the default) has not chosen yet.
     */
    public function __construct(
        public readonly string $action,
        public readonly AjaxAccess $access = AjaxAccess::LOGGED,
        public readonly bool|string|null $nonce = null,
    ) {}
}
