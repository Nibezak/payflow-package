<?php

use Illuminate\Support\Facades\Route;

Route::post(config('payflow.stripe.webhook_path', 'stripe/webhook'), \Payflow\Stripe\Http\Controllers\WebhookController::class)
    ->middleware([\Payflow\Stripe\Http\Middleware\StripeWebhookMiddleware::class, 'api'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('payflow.stripe.webhook');
