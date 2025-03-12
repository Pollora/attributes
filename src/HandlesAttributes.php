<?php
declare(strict_types=1);

namespace Pollora\Attributes;

use ReflectionMethod;
use ReflectionClass;

interface HandlesAttributes
{
    public function handle(Attributable $instance, ReflectionClass|ReflectionMethod $context, object $attribute): void;
}
