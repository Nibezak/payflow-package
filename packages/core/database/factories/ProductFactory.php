<?php

namespace Payflow\Database\Factories;

use Payflow\FieldTypes\Text;
use Payflow\Models\Brand;
use Payflow\Models\Product;
use Payflow\Models\ProductType;

class ProductFactory extends BaseFactory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'product_type_id' => ProductType::factory(),
            'status' => 'published',
            'brand_id' => Brand::factory()->create()->id,
            'attribute_data' => collect([
                'name' => new Text($this->faker->name),
                'description' => new Text($this->faker->sentence),
            ]),
        ];
    }
}
