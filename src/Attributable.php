<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Pollora\Attributes\WpRestRoute\Permission;

/**
 * Marker interface that allows a class to be interpreted for PHP attributes.
 *
 * Classes implementing this interface can be processed by discovery services
 * to analyze and handle their attributes dynamically.
 *
 * @property class-string<Permission>|Permission|null $classPermission
 * @property string $namespace
 * @property string $route
 */
interface Attributable {}
