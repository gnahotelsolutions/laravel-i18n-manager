<?php

namespace GNAHotelSolutions\LaravelI18nManager;

use GNAHotelSolutions\LaravelI18nManager\Commands\Exportcommand;
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
                Exportcommand::class
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-i18n-manager');
    }
}
