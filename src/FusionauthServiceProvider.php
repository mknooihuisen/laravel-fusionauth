<?php

namespace Mknooihuisen\LaravelFusionauth;

use FusionAuth\FusionAuthClient;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\ErrorHandler\Error\FatalError;

class FusionauthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-fusionauth');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-fusionauth');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/fusionauth.php' => config_path('fusionauth.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-fusionauth'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-fusionauth'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-fusionauth'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/fusionauth.php', 'fusionauth');


        // Register the main class to use with the facade
        $this->app->singleton('laravel-fusionauth', function () {
            return new Fusionauth;
        });
    }
}
