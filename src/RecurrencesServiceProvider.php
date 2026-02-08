<?php

namespace VincenzoRaco\Recurrences;

use Illuminate\Support\ServiceProvider;

class RecurrencesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('recurrences.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'recurrences');

        // Register the main class to use with the facade
        $this->app->singleton('recurrences', function () {
            return new RecurrencesService;
        });
    }
}
