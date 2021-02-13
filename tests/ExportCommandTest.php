<?php

namespace GNAHotelSolutions\LaravelI18nManager\Tests;

use GNAHotelSolutions\LaravelI18nManager\I18nManagerServiceProvider;
use Orchestra\Testbench\TestCase;

class ExportCommandTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [I18nManagerServiceProvider::class];
    }

    public function test_true_is_true()
    {
        $this->assertTrue(true);
    }
}
