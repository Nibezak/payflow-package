<?php

namespace Payflow\Database\Factories;

use Illuminate\Support\Str;
use Payflow\Models\Channel;

class ChannelFactory extends BaseFactory
{
    protected $model = Channel::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'handle' => Str::slug($this->faker->name()),
            'default' => true,
            'url' => $this->faker->url(),
        ];
    }
}
