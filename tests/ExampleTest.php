<?php

namespace Gnahotelsolutions\LaravelI18nManager\Tests;

use Orchestra\Testbench\TestCase;
use Gnahotelsolutions\LaravelI18nManager\I18nManagerServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [I18nManagerServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
