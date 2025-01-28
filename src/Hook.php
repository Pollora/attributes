<?php

declare(strict_types=1);

namespace Pollora\Attributes;

abstract class Hook
{
    public function __construct(
        public string $hook,
        public int $priority = 10
    ) {}
}
