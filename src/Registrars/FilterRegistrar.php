<?php
namespace Pollora\Attributes\Registrars;

use Pollora\Attributes\Filter as FilterAttribute;
use Pollora\Support\Facades\Filter;

class FilterRegistrar
{
    public static function handle(object $instance, \ReflectionMethod $method, FilterAttribute $attributeInstance): void
    {
        Filter::add(
            $attributeInstance->hook,
            [$instance, $method->getName()],
            $attributeInstance->priority,
            $method->getNumberOfParameters()
        );
    }
}
