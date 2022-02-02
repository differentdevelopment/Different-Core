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
        /* Csak akkor bootoljunk ha látjuk a Backpack telepítés jeleit */
        /*if (empty(config("backpack"))) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        }*/


        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'patent oauth client');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'different-core');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'different-core');

        if ($this->app->runningInConsole()) {
            /*$this->publishes([
                __DIR__ . '/../config/config.php' => config_path('patent-oauth-client.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/patent oauth client'),
            ], 'views');

            // Publishing assets.
            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/patent oauth client'),
            ], 'assets');

            // Publishing the translation files.
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/patent oauth client'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                SeederCommand::class,
            ]);
        }
        
        // register the helper functions
        // $this->loadHelpers();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'different-core');
        $this->mergeConfigFrom(__DIR__ . '/../config/activitylog.php', 'activitylog');
        $this->mergeConfigFrom(__DIR__ . '/../config/permission.php', 'permission');
        $this->mergeConfigFrom(__DIR__ . '/../config/permissionmanager.php', 'permissionmanager');

        // Register the main class to use with the facade
        $this->app->singleton('different-core', function () {
            return new DifferentCore;
        });
    }

    /**
     * Load the Backpack helper methods, for convenience.
     */
    /*public function loadHelpers()
    {
        require_once __DIR__ . '/helpers.php';
    }*/
}
