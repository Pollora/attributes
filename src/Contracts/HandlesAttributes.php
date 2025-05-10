<?php

declare(strict_types=1);

namespace Pollora\Attributes\Contracts;

use ReflectionClass;
use ReflectionMethod;
use Pollora\Attributes\Attributable;

interface HandlesAttributes
{
    public function handle($container, Attributable $instance, ReflectionClass|ReflectionMethod $context, object $attribute): void;
}
