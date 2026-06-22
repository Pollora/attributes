<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Pollora\Ajax\Domain\Model\AjaxAction;

/**
 * Enum defining the audience targeting for an AJAX action attribute.
 *
 * Used as the `access` parameter of the {@see Ajax} attribute to control
 * which WordPress hooks are registered (`wp_ajax_*`, `wp_ajax_nopriv_*`, or both).
 *
 * Defaults to {@see self::LOGGED} (security-by-default): endpoints are not
 * exposed to unauthenticated visitors unless explicitly opted in.
 *
 * @see Ajax
 */
enum AjaxAccess: string
{
    /**
     * Logged-in users only — registers `wp_ajax_{action}`.
     * This is the default when no access is specified.
     */
    case LOGGED = 'logged';

    /**
     * Guest (unauthenticated) users only — registers `wp_ajax_nopriv_{action}`.
     */
    case GUEST = 'guest';

    /**
     * All users (logged-in and guests) — registers both hooks.
     * Must be opted into explicitly.
     */
    case ALL = 'both';

    /**
     * Apply this access level to the given AJAX action domain model.
     *
     * @param  AjaxAction  $action  The action to configure.
     * @return AjaxAction The configured action (fluent).
     */
    public function applyTo(AjaxAction $action): AjaxAction
    {
        return match ($this) {
            self::LOGGED => $action->forLoggedUsers(),
            self::GUEST => $action->forGuestUsers(),
            self::ALL => $action->forAllUsers(),
        };
    }
}
