<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use ReflectionClass;
use ReflectionMethod;
use Pollora\Hook\Infrastructure\Services\Filter as FilterService;

/**
 * Class Filter
 *
 * Attribute for WordPress filters.
 * This class is used to define a filter hook in WordPress.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Filter extends Hook
{
    /**
     * Constructor for the Filter attribute.
     *
     * @param string $hook The name of the WordPress hook.
     * @param int $priority The priority of the hook.
     */
    public function __construct(
        string $hook,
        int $priority = 10
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
        object $attribute
    ): void {
        // Récupérer le service Filter depuis le service locator
        $filterService = $serviceLocator->resolve(FilterService::class);
        if (!$filterService) {
            return;
        }

        $filterService->add(
            $attribute->hook,
            [$instance, $context->getName()],
            $attribute->priority,
            $context->getNumberOfParameters()
        );
    }
}
