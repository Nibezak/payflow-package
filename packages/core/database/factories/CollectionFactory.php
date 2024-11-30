<?php

namespace Payflow\Database\Factories;

use Payflow\FieldTypes\Text;
use Payflow\Models\Collection;
use Payflow\Models\CollectionGroup;

class CollectionFactory extends BaseFactory
{
    protected $model = Collection::class;

    public function definition(): array
    {
        return [
            'collection_group_id' => CollectionGroup::factory(),
            'attribute_data' => collect([
                'name' => new Text($this->faker->name),
            ]),
        ];
    }
}
