<?php

namespace Gnahotelsolutions\LaravelI18nExporter;

use Illuminate\Support\ServiceProvider;

class I18nManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-i18n-manager.php'),
            ], 'config');


            $this->commands([
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-i18n-manager');
    }
}
