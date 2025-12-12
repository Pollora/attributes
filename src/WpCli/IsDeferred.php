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
final readonly class IsDeferred
{
    /**
     * Create a new Is Deferred attribute.
     *
     * @param  bool  $deferred  Whether the command addition should be deferred
     */
    public function __construct(
        public bool $deferred = true
    ) {}
}
