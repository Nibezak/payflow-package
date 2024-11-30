<?php

namespace Payflow\Stripe\Concerns;

interface ConstructsWebhookEvent
{
    public function constructEvent(string $jsonPayload, string $signature, string $secret);
}
