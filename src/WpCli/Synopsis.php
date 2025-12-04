<?php

declare(strict_types=1);

namespace Pollora\Attributes\WpCli;

use Attribute;

/**
 * WP CLI Synopsis Attribute
 *
 * Defines the synopsis for the command.
 * Can be a string or an array defining command arguments and options.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Synopsis
{
    /**
     * Create a new Synopsis attribute.
     *
     * @param  string|array  $synopsis  The synopsis for the command
     */
    public function __construct(
        public readonly string|array $synopsis
    ) {}
}
