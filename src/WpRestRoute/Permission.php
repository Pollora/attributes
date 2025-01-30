<?php
namespace Pollora\Attributes\WpRestRoute;

use WP_REST_Request;

interface Permission
{
    /**
     * Checks if the user has permission to access this route/action.
     * Returns `true` to allow, `false` to deny,
     * or a `WP_Error` for specific errors.
     *
     * @param WP_REST_Request $request The REST request instance.
     * @return bool|\WP_Error True if allowed, false if denied, or WP_Error on error.
     */
    public function allow(WP_REST_Request $request): bool|\WP_Error;
}
