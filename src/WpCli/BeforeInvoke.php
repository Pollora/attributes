<?php

declare(strict_types=1);

namespace Pollora\Attributes\WpCli;

use Attribute;

/**
 * WP CLI Before Invoke Attribute
 *
 * Defines a callback to execute before invoking the command.
 * The callback can be a method name or a callable array.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class BeforeInvoke
{
    /**
     * Create a new Before Invoke attribute.
     *
     * @param string|callable $callback The callback to execute before command invocation
     */
    public function __construct(
        public readonly string|array $callback
    ) {}
}