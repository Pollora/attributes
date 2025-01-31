<?php
declare(strict_types=1);

namespace Pollora\Attributes;

use Pollora\Support\Facades\Filter as FilterFacade;
use Attribute;
use ReflectionMethod;

/**
 * Class Filter
 *
 * Attribute for WordPress filters.
 * This class is used to define a filter hook in WordPress.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Filter extends Hook
{
    public static function handle(object $instance, ReflectionMethod $method, Filter $attributeInstance): void
    {
        FilterFacade::add(
            $attributeInstance->hook,
            [$instance, $method->getName()],
            $attributeInstance->priority,
            $method->getNumberOfParameters()
        );
    }
}
