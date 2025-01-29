<?php
namespace Pollora\Attributes\Registrars;

use Pollora\Attributes\Action as ActionAttribute;
use Pollora\Support\Facades\Action;

class ActionRegistrar
{
    public static function handle(object $instance, \ReflectionMethod $method, ActionAttribute $attributeInstance): void
    {
        Action::add(
            $attributeInstance->hook,
            [$instance, $method->getName()],
            $attributeInstance->priority,
            $method->getNumberOfParameters()
        );
    }
}
