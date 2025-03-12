<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use ReflectionClass;
use ReflectionMethod;

interface HandlesAttributes
{
    public function handle(Attributable $instance, ReflectionClass|ReflectionMethod $context, object $attribute): void;
}
