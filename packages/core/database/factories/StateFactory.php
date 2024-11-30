<?php

namespace Payflow\Database\Factories;

use Illuminate\Support\Str;
use Payflow\Models\State;

class StateFactory extends BaseFactory
{
    protected $model = State::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->country,
            'code' => Str::random(),
        ];
    }
}
