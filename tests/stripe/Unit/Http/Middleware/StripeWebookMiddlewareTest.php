<?php

uses(\Payflow\Tests\Stripe\Unit\TestCase::class)->group('payflow.stripe.middleware');

it('can handle valid event', function () {
    $this->app->bind(\Payflow\Stripe\Concerns\ConstructsWebhookEvent::class, function ($app) {
        return new class implements \Payflow\Stripe\Concerns\ConstructsWebhookEvent
        {
            public function constructEvent(string $jsonPayload, string $signature, string $secret)
            {
                return \Stripe\Event::constructFrom([
                    'type' => 'payment_intent.succeeded',
                ]);
            }
        };
    });

    $request = \Illuminate\Http\Request::create('/strip-webhook', 'POST');
    $request->headers->set('Stripe-Signature', 'foobar');
    $middleware = new \Payflow\Stripe\Http\Middleware\StripeWebhookMiddleware([]);

    $request = $middleware->handle($request, fn ($request) => $request);

    expect($request)->toBeInstanceOf(\Illuminate\Http\Request::class);
});
