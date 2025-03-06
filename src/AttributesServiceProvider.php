<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider for WordPress hook functionality.
 *
 * Manages the registration and bootstrapping of WordPress hooks system,
 * including actions and filters, within the Laravel application context.
 */
class AttributesServiceProvider extends ServiceProvider
{
    /**
     * Register hook-related services in the application.
     *
     * Binds hook configurations and hook implementations as singletons
     * in the application container.
     */
    public function register(): void
    {
        $this->app->resolving(Attributable::class, function ($object) {
            AttributeProcessor::process($object);
        });
    }
}
