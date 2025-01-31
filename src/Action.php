<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Pollora\Support\Facades\Action as ActionFacade;
use Attribute;
use ReflectionMethod;

/**
 * Class Action
 *
 * Attribute for WordPress actions.
 * This class is used to define an action hook in WordPress.
 */

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Action extends Hook
{
    public static function handle(object $instance, ReflectionMethod $method, Action $attributeInstance): void
    {
        ActionFacade::add(
            $attributeInstance->hook,
            [$instance, $method->getName()],
            $attributeInstance->priority,
            $method->getNumberOfParameters()
        );
    }
}
