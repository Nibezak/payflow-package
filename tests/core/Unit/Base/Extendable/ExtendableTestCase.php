<?php

namespace Payflow\Tests\Core\Unit\Base\Extendable;

use Payflow\Facades\ModelManifest;
use Payflow\Models\Product;
use Payflow\Models\ProductOption;
use Payflow\Models\ProductOptionValue;
use Payflow\Tests\Core\TestCase;

class ExtendableTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ModelManifest::register(collect([
            Product::class => \Payflow\Tests\Core\Stubs\Models\Product::class,
            ProductOption::class => \Payflow\Tests\Core\Stubs\Models\ProductOption::class,
        ]));

        Product::factory()->count(20)->create();

        ProductOption::factory()
            ->has(ProductOptionValue::factory()->count(3), 'values')
            ->create([
                'name' => [
                    'en' => 'Size',
                ],
            ]);
    }
}
