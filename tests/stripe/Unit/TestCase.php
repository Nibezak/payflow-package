<?php

namespace Payflow\Tests\Stripe\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Payflow\Tests\Stripe\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }
}
