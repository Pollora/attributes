<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Pollora\Support\Facades\Filter as FilterFacade;
use ReflectionMethod;
use ReflectionClass;

/**
 * Class Filter
 *
 * Attribute for WordPress filters.
 * This class is used to define a filter hook in WordPress.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Filter extends Hook
{
    public function handle(object $instance, ReflectionMethod|\ReflectionClass $context, object $attribute): void
    {
        FilterFacade::add(
            $attribute->hook,
            [$instance, $context->getName()],
            $attribute->priority,
            $context->getNumberOfParameters()
        );
    }
}
