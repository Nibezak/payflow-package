<?php

namespace Payflow\Stripe;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Payflow\Facades\Payments;
use Payflow\Models\Cart;
use Payflow\Stripe\Actions\ConstructWebhookEvent;
use Payflow\Stripe\Components\PaymentForm;
use Payflow\Stripe\Concerns\ConstructsWebhookEvent;
use Payflow\Stripe\Managers\StripeManager;
use Payflow\Stripe\Models\StripePaymentIntent;

class StripePaymentsServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Register our payment type.
        Payments::extend('stripe', function ($app) {
            return $app->make(StripePaymentType::class);
        });

        Cart::resolveRelationUsing('paymentIntents', function (Cart $cart) {
            return $cart->hasMany(StripePaymentIntent::class);
        });

        $this->app->bind(ConstructsWebhookEvent::class, function ($app) {
            return $app->make(ConstructWebhookEvent::class);
        });

        $this->app->singleton('payflow:stripe', function ($app) {
            return $app->make(StripeManager::class);
        });

        Blade::directive('stripeScripts', function () {
            return <<<'EOT'
                <script src="https://js.stripe.com/v3/"></script>
            EOT;
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'payflow');
        $this->loadRoutesFrom(__DIR__.'/../routes/webhooks.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config/stripe.php', 'payflow.stripe');

        $this->publishes([
            __DIR__.'/../config/stripe.php' => config_path('payflow/stripe.php'),
        ], 'payflow.stripe.config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/payflow'),
        ], 'payflow.stripe.components');

        if (class_exists(Livewire::class)) {
            // Register the stripe payment component.
            Livewire::component('stripe.payment', PaymentForm::class);
        }
    }
}
