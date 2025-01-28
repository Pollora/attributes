<?php
declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;

/**
 * Class Filter
 *
 * Attribute for WordPress filters.
 * This class is used to define a filter hook in WordPress.
 */
#[Attribute]
class Filter extends Hook
{
}
