<?php

namespace Payflow\Stripe\Actions;

use Payflow\Stripe\Concerns\ConstructsWebhookEvent;
use Stripe\Webhook;

class ConstructWebhookEvent implements ConstructsWebhookEvent
{
    public function constructEvent(string $jsonPayload, string $signature, string $secret)
    {
        return Webhook::constructEvent(
            $jsonPayload, $signature, $secret
        );
    }
}
