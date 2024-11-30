<?php

namespace Payflow\Database\Factories;

use Illuminate\Support\Str;
use Payflow\Models\Product;
use Payflow\Models\ProductVariant;
use Payflow\Models\TaxClass;
use Payflow\Models\TaxRateAmount;

class ProductVariantFactory extends BaseFactory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'tax_class_id' => TaxClass::factory()->hasTaxRateAmounts(
                TaxRateAmount::factory()
            ),
            'sku' => Str::random(12),
            'unit_quantity' => 1,
            'gtin' => $this->faker->unique()->isbn13,
            'mpn' => $this->faker->unique()->isbn13,
            'ean' => $this->faker->unique()->ean13,
            'shippable' => true,
        ];
    }
}
