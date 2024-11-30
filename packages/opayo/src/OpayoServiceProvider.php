<?php

namespace Payflow\Opayo;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Payflow\Facades\Payments;
use Payflow\Opayo\Components\PaymentForm;

class OpayoServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Register our payment type.
        Payments::extend('opayo', function ($app) {
            return $app->make(OpayoPaymentType::class);
        });

        $this->app->singleton(OpayoInterface::class, function ($app) {
            return $app->make(Opayo::class);
        });

        $this->mergeConfigFrom(__DIR__.'/../config/opayo.php', 'payflow.opayo');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Blade::directive('opayoScripts', function ($incVendor = true) {
            $url = 'https://sandbox.opayo.eu.elavon.com/api/v1/js/sagepay.js';

            $manifest = json_decode(file_get_contents(__DIR__.'/../dist/mix-manifest.json'), true);

            $jsUrl = asset('/vendor/opayo'.$manifest['/opayo.js']);

            if (strtolower(config('services.opayo.env', 'test')) == 'live') {
                $url = 'https://live.opayo.eu.elavon.com/api/v1/js/sagepay.js';
            }

            $manifest = json_decode(file_get_contents(__DIR__.'/../dist/mix-manifest.json'), true);

            $jsUrl = asset('/vendor/payflow'.$manifest['/opayo.js']);

            if (! $incVendor) {
                return <<<EOT
                <script src="{$url}"></script>
            EOT;
            }

            return <<<EOT
                <script src="{$jsUrl}" async></script>
                <script src="{$url}" async></script>
            EOT;
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'payflow');

        $this->publishes([
            __DIR__.'/../config/opayo.php' => config_path('payflow/opayo.php'),
        ], 'payflow.opayo.config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/payflow'),
        ], 'payflow.opayo.components');

        $this->publishes([
            __DIR__.'/../dist' => public_path('vendor/payflow'),
        ], 'payflow.opayo.public');

        // Register the stripe payment component.
        Livewire::component('opayo.payment', PaymentForm::class);
    }
}
