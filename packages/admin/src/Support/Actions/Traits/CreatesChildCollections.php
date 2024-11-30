<?php

namespace Payflow\Admin\Support\Actions\Traits;

use Payflow\Facades\DB;
use Payflow\Models\Attribute;
use Payflow\Models\Collection;

trait CreatesChildCollections
{
    public function createChildCollection(Collection $parent, array|string $name)
    {
        DB::beginTransaction();

        $attribute = Attribute::whereHandle('name')->whereAttributeType(
            Collection::morphName()
        )->first()->type;

        $parent->appendNode(Collection::create([
            'collection_group_id' => $parent->collection_group_id,
            'attribute_data' => [
                'name' => new $attribute($name),
            ],
        ]));

        DB::commit();
    }
}
