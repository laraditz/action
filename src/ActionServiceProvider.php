<?php

namespace Laraditz\Action;

use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // register commands
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('action.php'),
            ], 'config');

            $this->commands([
                Console\ActionMakeCommand::class,
            ]);
        }

        $this->app->make(__NAMESPACE__ . '\Action');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'action');
    }
}
