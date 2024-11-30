<?php

namespace Payflow\Tests\Core;

use Cartalyst\Converter\Laravel\ConverterServiceProvider;
use Illuminate\Support\Facades\Config;
use Kalnoy\Nestedset\NestedSetServiceProvider;
use Payflow\Facades\Taxes;
use Payflow\PayflowServiceProvider;
use Payflow\Tests\Core\Stubs\TestTaxDriver;
use Payflow\Tests\Core\Stubs\TestUrlGenerator;
use Payflow\Tests\Core\Stubs\User;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Spatie\LaravelBlink\BlinkServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        // additional setup
        Config::set('providers.users.model', User::class);
        Config::set('payflow.urls.generator', TestUrlGenerator::class);
        Config::set('payflow.taxes.driver', 'test');
        Config::set('payflow.media.collection', 'images');

        Taxes::extend('test', function ($app) {
            return $app->make(TestTaxDriver::class);
        });

        activity()->disableLogging();

        // Freeze time to avoid timestamp errors
        $this->freezeTime();
    }

    protected function getPackageProviders($app)
    {
        return [
            PayflowServiceProvider::class,
            MediaLibraryServiceProvider::class,
            ActivitylogServiceProvider::class,
            ConverterServiceProvider::class,
            NestedSetServiceProvider::class,
            BlinkServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
