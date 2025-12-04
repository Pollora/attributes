<?php

declare(strict_types=1);

namespace Pollora\Attributes\WpCli;

use Attribute;

/**
 * WP CLI Is Deferred Attribute
 *
 * Indicates whether the command addition should be deferred.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class IsDeferred
{
    /**
     * Create a new Is Deferred attribute.
     *
     * @param  bool  $deferred  Whether the command addition should be deferred
     */
    public function __construct(
        public readonly bool $deferred = true
    ) {}
}
