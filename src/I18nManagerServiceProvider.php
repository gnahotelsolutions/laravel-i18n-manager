<?php

namespace GNAHotelSolutions\LaravelI18nManager;

use GNAHotelSolutions\LaravelI18nManager\Commands\Exportcommand;
use Illuminate\Support\ServiceProvider;

class I18nManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Exportcommand::class
            ]);
        }
    }

    public function register()
    {
    }
}
