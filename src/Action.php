<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Pollora\Support\Facades\Action as ActionFacade;
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
    /**
     * Constructor for the Action attribute.
     *
     * @param  string  $hook  The name of the WordPress hook.
     * @param  int  $priority  The priority of the hook.
     */
    public function __construct(string $hook, int $priority = 10)
    {
        parent::__construct($hook, $priority);
    }

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
