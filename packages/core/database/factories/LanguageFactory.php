<?php

namespace Payflow\Database\Factories;

use Payflow\Models\Language;

class LanguageFactory extends BaseFactory
{
    protected $model = Language::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->languageCode,
            'name' => $this->faker->name(),
            'default' => true,
        ];
    }
}
