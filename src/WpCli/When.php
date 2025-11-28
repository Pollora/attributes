<?php

declare(strict_types=1);

namespace Pollora\Attributes\WpCli;

use Attribute;

/**
 * WP CLI When Attribute
 *
 * Execute callback on a named WP-CLI hook.
 * Common hooks include: before_wp_load, after_wp_load, after_wp_config_load
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class When
{
    /**
     * Create a new When attribute.
     *
     * @param string $hook The WP-CLI hook name to execute on
     */
    public function __construct(
        public readonly string $hook
    ) {}
}