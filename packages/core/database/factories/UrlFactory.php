<?php

namespace Payflow\Database\Factories;

use Payflow\Models\Language;
use Payflow\Models\Product;
use Payflow\Models\Url;

class UrlFactory extends BaseFactory
{
    protected $model = Url::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug,
            'default' => true,
            'language_id' => Language::factory(),
            'element_type' => Product::morphName(),
            'element_id' => 1,
        ];
    }
}
