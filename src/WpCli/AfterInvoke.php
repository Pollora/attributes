<?php

declare(strict_types=1);

namespace Pollora\Attributes\WpCli;

use Attribute;

/**
 * WP CLI After Invoke Attribute
 *
 * Defines a callback to execute after invoking the command.
 * The callback can be a method name or a callable array.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class AfterInvoke
{
    /**
     * Create a new After Invoke attribute.
     *
     * @param string|callable $callback The callback to execute after command invocation
     */
    public function __construct(
        public readonly string|array $callback
    ) {}
}