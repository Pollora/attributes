<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use ReflectionClass;
use ReflectionMethod;
use Pollora\Hook\Infrastructure\Services\Action as ActionService;

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
     * @param string $hook The name of the WordPress hook.
     * @param int $priority The priority of the hook.
     */
    public function __construct(
        string $hook,
        int $priority = 10,
    ) {
        parent::__construct($hook, $priority);
    }

    /**
     * Handle the attribute processing.
     *
     * @param object $serviceLocator Le service locator pour résoudre les dépendances
     * @param object $instance The instance being processed
     * @param ReflectionMethod|ReflectionClass $context The reflection context
     * @param object $attribute The attribute instance
     */
    public function handle(
        $serviceLocator,
        object $instance,
        ReflectionMethod|ReflectionClass $context,
        object $attribute,
    ): void {
        // Récupérer le service Action depuis le service locator
        $actionService = $serviceLocator->resolve(ActionService::class);
        if (!$actionService) {
            return;
        }

        $actionService->add(
            $attribute->hook,
            [$instance, $context->getName()],
            $attribute->priority,
            $context->getNumberOfParameters()
        );
    }
}
