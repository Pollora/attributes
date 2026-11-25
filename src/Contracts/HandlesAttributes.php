<?php

declare(strict_types=1);

namespace Pollora\Attributes\Contracts;

use Pollora\Attributes\Attributable;
use ReflectionClass;
use ReflectionMethod;

interface HandlesAttributes
{
    /**
     * @param  mixed  $container  The container the handler resolves what it needs from
     * @param  ReflectionClass<object>|ReflectionMethod  $context
     */
    public function handle($container, Attributable $instance, ReflectionClass|ReflectionMethod $context, object $attribute): void;
}
