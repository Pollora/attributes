<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Pollora\Support\Facades\Action as ActionFacade;
use ReflectionMethod;
use ReflectionClass;

/**
 * Class Action
 *
 * Attribute for WordPress actions.
 * This class is used to define an action hook in WordPress.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Action extends Hook
{
<<<<<<< Updated upstream
    public static function handle(object $instance, ReflectionMethod $method, Action $attributeInstance): void
=======
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

    public function handle(object $instance, ReflectionMethod|ReflectionClass $context, object $attribute): void
>>>>>>> Stashed changes
    {
        ActionFacade::add(
            $attribute->hook,
            [$instance, $context->getName()],
            $attribute->priority,
            $context->getNumberOfParameters()
        );
    }
}
