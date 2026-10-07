<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Pollora\Hook\Domain\Contract\Action as ActionService;
use Pollora\Hook\Infrastructure\Services\AsyncAttributeRegistrar;
use Psr\Log\LoggerInterface;
use ReflectionClass;
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
     * Handle the attribute processing.
     *
     * @param  object  $serviceLocator  Service locator used to resolve dependencies
     * @param  object  $instance  The instance being processed
     * @param  ReflectionMethod|ReflectionClass  $context  The reflection context
     * @param  object  $attribute  The attribute instance
     */
    public function handle(
        $serviceLocator,
        object $instance,
        ReflectionMethod|ReflectionClass $context,
        object $attribute,
    ): void {
        // Retrieve the Action service from the locator
        $actionService = $serviceLocator->get(ActionService::class);
        if (! $actionService) {
            return;
        }

        // #[Action] targets methods only
        if (! $context instanceof ReflectionMethod) {
            return;
        }

        // Asynchronously when the method or its class carries #[Async]
        (new AsyncAttributeRegistrar($actionService, $this->logger($serviceLocator)))->register(
            $attribute->hook,
            $instance,
            $context,
            $attribute->priority,
            $context->getNumberOfParameters()
        );
    }

    /**
     * The application logger, when the service locator provides one.
     */
    private function logger(object $serviceLocator): ?LoggerInterface
    {
        try {
            $logger = $serviceLocator->get(LoggerInterface::class);
        } catch (\Throwable) {
            return null;
        }

        return $logger instanceof LoggerInterface ? $logger : null;
    }
}
