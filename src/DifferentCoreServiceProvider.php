<?php

namespace Different\DifferentCore;

use Different\DifferentCore\app\Console\Commands\SeederCommand;
use Different\DifferentCore\app\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class DifferentCoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'different-core');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'different-core');

        Blade::componentNamespace('Different\\DifferentCore\\app\\View\\Components', 'different-core');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config' => config_path(),
            ], 'config');
            $this->publishes([
                __DIR__.'/resources/scss' => resource_path('scss'),
            ], 'scss');
            $this->publishes([
                __DIR__.'/resources/lang' => resource_path('lang/vendor/different-core'),
            ], 'lang');

            $this->commands([
                SeederCommand::class,
            ]);
        }

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/config/different-core/config.php', 'different-core.config');
        $this->mergeConfigFrom(__DIR__.'/config/different-core/activitylog.php', 'activitylog');
        $this->mergeConfigFrom(__DIR__.'/config/different-core/permission.php', 'permission');

        // Register the main class to use with the facade
        $this->app->singleton('different-core', function () {
            return new DifferentCore;
        });

        if(config('different-core.config.project_uses_core_error_handling', true) == true)
        {
            $this->app->bind(
                ExceptionHandler::class,
                Handler::class,
            );
        }
    }
}
