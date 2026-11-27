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
 * that name, `false` turns it off on purpose. What happens to an action that does
 * not say depends on the installed pollora/ajax: 1.x runs it without a check
 * (and reports it from 1.2), 2.0 verifies a nonce named after the action.
 *
 * The capability is the WordPress capability a logged-in user must have for the
 * action to run (a nonce proves where the request comes from, not that the user
 * may do what it asks). It is checked after the nonce and needs pollora/ajax 1.3.
 * A capability and guest access are contradictory: guests are refused with a 401.
 *
 * Usage:
 *
 *     #[Ajax('subscribe')]                              // logged-in only (default), nonce not chosen (see above)
 *     #[Ajax('save_note', nonce: true)]                 // logged-in only, verifies the nonce `save_note`
 *     #[Ajax('save_note', nonce: 'notes_form')]         // verifies the nonce `notes_form`
 *     #[Ajax('load_more', access: AjaxAccess::ALL)]     // all users
 *     #[Ajax('track_visit', access: AjaxAccess::GUEST, nonce: false)] // guests only, no nonce on purpose
 *
 *     #[Ajax('save_note', nonce: true, capability: 'edit_posts')]    // logged-in users who can edit posts
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
     * @param  string|null  $capability  The capability a logged-in user must have, `null` (the default) requires none.
     */
    public function __construct(
        public readonly string $action,
        public readonly AjaxAccess $access = AjaxAccess::LOGGED,
        public readonly bool|string|null $nonce = null,
        public readonly ?string $capability = null,
    ) {}
}
