<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;

/**
 * Class Action
 *
 * Attribute for WordPress actions.
 * This class is used to define an action hook in WordPress.
 */

#[Attribute]
class Action extends Hook
{
}
