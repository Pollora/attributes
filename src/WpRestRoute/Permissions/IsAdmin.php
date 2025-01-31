<?php
namespace Pollora\Attributes\WpRestRoute\Permissions;

use Pollora\Attributes\WpRestRoute\Permission;
use WP_REST_Request;
use WP_Error;

class IsAdmin implements Permission
{
    /**
     * Checks if the current user has admin permissions.
     *
     * @param WP_REST_Request $request The REST request instance.
     * @return bool|\WP_Error True if the user is an admin, WP_Error otherwise.
     */
    public function allow(WP_REST_Request $request): bool|WP_Error
    {
        return current_user_can('manage_options') ?: new WP_Error(
            'rest_forbidden',
            __('You do not have permission to access this endpoint.'),
            ['status' => 403]
        );
    }
}
