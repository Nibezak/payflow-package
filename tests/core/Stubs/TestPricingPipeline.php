<?php

namespace Payflow\Tests\Core\Stubs;

use Closure;
use Payflow\Base\PricingManagerInterface;

class TestPricingPipeline
{
    public function handle(PricingManagerInterface $pricingManager, Closure $next)
    {
        $matchedPrice = $pricingManager->pricing->matched;

        $matchedPrice->price->value = 200;

        $pricingManager->pricing->matched = $matchedPrice;

        return $next($pricingManager);
    }
}
