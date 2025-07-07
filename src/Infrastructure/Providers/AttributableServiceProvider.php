<?php

declare(strict_types=1);

namespace Pollora\Attributes\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Pollora\Attributes\Attributable;
use Pollora\Attributes\AttributeProcessor;
use Pollora\Attributes\Infrastructure\Services\AttributableDiscovery;
use Pollora\Discovery\Application\Services\DiscoveryManager;
use Pollora\Discovery\Domain\Contracts\DiscoveryEngineInterface;

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
        // Register Attributable Discovery
        $this->app->singleton(AttributableDiscovery::class);
    }

    /**
     * Bootstrap services.
     *
     * Processes discovered Attributable classes and registers them with WordPress.
     */
    public function boot(): void
    {
        $this->registerAttributableClasses();

        // Register Attributable discovery with the discovery engine
        $this->registerAttributableDiscovery();
    }

    /**
     * Register all Attributable classes using the new discovery system.
     */
    protected function registerAttributableClasses(): void
    {
        try {
            /** @var DiscoveryManager $discoveryManager */
            $discoveryManager = $this->app->make(DiscoveryManager::class);

            // Check if attributable discovery is available
            if (! $discoveryManager->hasDiscovery('attributable')) {
                return;
            }

            $attributableItems = $discoveryManager->getDiscoveredItems('attributable');

            if (empty($attributableItems)) {
                return;
            }

            $processor = new AttributeProcessor($this->app);

            foreach ($attributableItems as $attributableItem) {
                $attributableClass = $attributableItem['class'] ?? null;
                if ($attributableClass) {
                    $this->registerAttributableClass($attributableClass, $processor);
                }
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

    /**
     * Register Attributable discovery with the discovery engine.
     */
    private function registerAttributableDiscovery(): void
    {
        if ($this->app->bound(DiscoveryEngineInterface::class)) {
            /** @var DiscoveryEngineInterface $engine */
            $engine = $this->app->make(DiscoveryEngineInterface::class);
            $attributableDiscovery = $this->app->make(AttributableDiscovery::class);

            $engine->addDiscovery('attributable', $attributableDiscovery);
        }
    }
}
