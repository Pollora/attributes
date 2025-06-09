<?php

declare(strict_types=1);

namespace Pollora\Attributes\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Pollora\Attributes\Attributable;
use Pollora\Attributes\AttributeProcessor;
use Pollora\Discoverer\Framework\API\PolloraDiscover;

/**
 * Service provider for attribute-based class registration.
 *
 * This provider processes Attributable classes discovered by the Discoverer system
 * and registers them with WordPress following hexagonal architecture principles.
 * It ensures all classes implementing the Attributable interface have their
 * attributes processed automatically.
 */
class AttributableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // No registration needed as the main service provider handles it
    }

    /**
     * Bootstrap services.
     *
     * Processes discovered Attributable classes and registers them with WordPress.
     */
    public function boot(): void
    {
        $this->registerAttributableClasses();
    }

    /**
     * Register all Attributable classes using the new discovery system.
     */
    protected function registerAttributableClasses(): void
    {
        try {
            $attributableClasses = PolloraDiscover::scout('attributable');

            if ($attributableClasses->isEmpty()) {
                return;
            }

            $processor = new AttributeProcessor($this->app);

            foreach ($attributableClasses as $attributableClass) {
                $this->registerAttributableClass($attributableClass, $processor);
            }
        } catch (\Throwable $e) {
            // Log error but don't break the application
            if (function_exists('error_log')) {
                error_log('Failed to load Attributable classes: '.$e->getMessage());
            }
        }
    }

    /**
     * Register a single Attributable class.
     *
     * @param  string  $attributableClass  The fully qualified class name of the Attributable class
     * @param  AttributeProcessor  $processor  The attribute processor
     */
    protected function registerAttributableClass(
        string $attributableClass,
        AttributeProcessor $processor
    ): void {
        $attributableInstance = $this->app->make($attributableClass);

        if (! $attributableInstance instanceof Attributable) {
            return;
        }

        // Process attributes - this will handle the registration automatically
        // through the AttributeProcessor
        $processor->process($attributableInstance);
    }
}
