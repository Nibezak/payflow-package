<?php

use Illuminate\Support\Facades\Schema;
use Payflow\Base\Migration;

class RemapPolymorphicRelations extends Migration
{
    public function up()
    {
        $modelClasses = collect([
            \Payflow\Models\CartLine::class,
            \Payflow\Models\ProductOption::class,
            \Payflow\Models\Asset::class,
            \Payflow\Models\Brand::class,
            \Payflow\Models\TaxZone::class,
            \Payflow\Models\TaxZoneCountry::class,
            \Payflow\Models\TaxZoneCustomerGroup::class,
            \Payflow\Models\DiscountCollection::class,
            \Payflow\Models\TaxClass::class,
            \Payflow\Models\ProductOptionValue::class,
            \Payflow\Models\Channel::class,
            \Payflow\Models\AttributeGroup::class,
            \Payflow\Models\Tag::class,
            \Payflow\Models\Cart::class,
            \Payflow\Models\Collection::class,
            \Payflow\Models\Discount::class,
            \Payflow\Models\TaxRate::class,
            \Payflow\Models\Price::class,
            \Payflow\Models\DiscountPurchasable::class,
            \Payflow\Models\State::class,
            \Payflow\Models\UserPermission::class,
            \Payflow\Models\OrderAddress::class,
            \Payflow\Models\Country::class,
            \Payflow\Models\Address::class,
            \Payflow\Models\Url::class,
            \Payflow\Models\ProductVariant::class,
            \Payflow\Models\TaxZonePostcode::class,
            \Payflow\Models\ProductAssociation::class,
            \Payflow\Models\TaxRateAmount::class,
            \Payflow\Models\Attribute::class,
            \Payflow\Models\Order::class,
            \Payflow\Models\Customer::class,
            \Payflow\Models\OrderLine::class,
            \Payflow\Models\CartAddress::class,
            \Payflow\Models\Language::class,
            \Payflow\Models\TaxZoneState::class,
            \Payflow\Models\Currency::class,
            \Payflow\Models\Product::class,
            \Payflow\Models\Transaction::class,
            \Payflow\Models\ProductType::class,
            \Payflow\Models\CollectionGroup::class,
            \Payflow\Models\CustomerGroup::class,
        ])->mapWithKeys(
            fn ($class) => [
                $class => \Payflow\Facades\ModelManifest::getMorphMapKey($class),
            ]
        );

        $tables = [
            'attributables' => ['attributable_type'],
            'attributes' => ['attribute_type'],
            'attribute_groups' => ['attributable_type'],
            'cart_lines' => ['purchasable_type'],
            'channelables' => ['channelable_type'],
            'discount_purchasables' => ['purchasable_type'],
            'order_lines' => ['purchasable_type'],
            'prices' => ['priceable_type'],
            'taggables' => ['taggable_type'],
            'urls' => ['element_type'],
        ];

        $nonPayflowTables = [
            'activity_log' => 'subject_type',
            'media' => 'model_type',
            'model_has_permissions' => 'model_type',
            'model_has_roles' => 'model_type',
        ];

        foreach ($modelClasses as $modelClass => $mapping) {

            foreach ($nonPayflowTables as $table => $column) {
                if (! Schema::hasTable($table)) {
                    continue;
                }
                \Illuminate\Support\Facades\DB::table($table)
                    ->where($column, '=', $modelClass)
                    ->update([
                        $column => $mapping,
                    ]);
            }

            foreach ($tables as $tableName => $columns) {
                $table = \Illuminate\Support\Facades\DB::table(
                    $this->prefix.$tableName
                );

                foreach ($columns as $column) {
                    $table->where($column, '=', $modelClass)->update([
                        $column => $mapping,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        // ...
    }
}
