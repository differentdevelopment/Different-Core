<?php

namespace Different\DifferentCore;

use Illuminate\Support\ServiceProvider;
use Different\DifferentCore\app\Console\Commands\SeederCommand;

class DifferentCoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {   
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'different-core');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'different-core');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config' => config_path(),
            ], 'config');
            $this->publishes([
                __DIR__ . '/resources/scss' => resource_path('scss'),
            ], 'scss');
            $this->publishes([
                __DIR__ . '/resources/lang' => resource_path('lang/vendor/different-core'),
            ], 'lang');

            $this->commands([
                SeederCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/config/different-core/config.php', 'different-core');
        $this->mergeConfigFrom(__DIR__ . '/config/different-core/activitylog.php', 'activitylog');
        $this->mergeConfigFrom(__DIR__ . '/config/different-core/permission.php', 'permission');
        $this->mergeConfigFrom(__DIR__ . '/config/different-core/permissionmanager.php', 'permissionmanager');

        // Register the main class to use with the facade
        $this->app->singleton('different-core', function () {
            return new DifferentCore;
        });
    }
}
