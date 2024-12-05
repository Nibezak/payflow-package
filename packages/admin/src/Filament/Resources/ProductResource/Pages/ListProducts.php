<?php

namespace Payflow\Admin\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\Grid;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Model;
use Payflow\Admin\Filament\Resources\ProductResource;
use Payflow\Admin\Support\Pages\BaseListRecords;
use Payflow\Facades\DB;
use Payflow\Models\Attribute;
use Payflow\Models\Currency;
use Payflow\Models\Product;
use Payflow\Models\TaxClass;

class ListProducts extends BaseListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(true)
                ->form(static::createActionFormInputs())
                ->using(
                    fn (array $data, string $model) => static::createRecord($data, $model)
                )
                ->after(function ($record) {
                    // Update the `payflow_user_id` on the record just created.
                    $record->update([
                        'payflow_user_id' => auth()->id(), // Update the main table with the current user's ID.
                    ]);
    
                    // Update the `payflow_user_id` in the `payflow_products` table where `product_id` matches.
                    DB::table('payflow_products')
                        ->where('id', $record->id) // Assuming `product_id` in `payflow_products` links to `id` in the main table.
                        ->update([
                            'payflow_user_id' => auth()->id(),
                        ]);
    
                    // Redirect to the edit page for the created record.
                })
                ->successRedirectUrl(fn (Model $record): string => ProductResource::getUrl('edit', [
                    'record' => $record,
                ])),
        ];
    }
    
    
    public static function createActionFormInputs(): array
    {
        return [
            Grid::make(2)->schema([
                ProductResource::getBaseNameFormComponent(),
                ProductResource::getProductTypeFormComponent()->required(),
            ]),
            Grid::make(2)->schema([
                ProductResource::getSkuFormComponent(),
                ProductResource::getBasePriceFormComponent(),
            ]),
        ];
    }

    public static function createRecord(array $data, string $model): Model
    {
        // Get the authenticated user's ID

        $authUser = auth()->id();
        // Get the default currency
        $currency = Currency::getDefault();

        // Get the name attribute type
        $nameAttribute = Attribute::whereAttributeType(
            $model::morphName()
        )
            ->whereHandle('name')
            ->first()
            ->type;

        // Start a transaction
        DB::beginTransaction();

        // Create the product record
        $product = $model::create([
            'status' => 'draft',
            'product_type_id' => $data['product_type_id'],
            'payflow_user_id' => $authUser, // Store the user ID
            'attribute_data' => [
                'name' => new $nameAttribute($data['name']),
            ],
        ]);

        // Create a product variant
        $variant = $product->variants()->create([
            'tax_class_id' => TaxClass::getDefault()->id,
            'payflow_user_id' => $authUser, // Store the user ID
            'sku' => $data['sku'],
        ]);

        // Create the price for the variant
        $variant->prices()->create([
            'payflow_user_id' => $authUser, // Store the user ID
            'min_quantity' => 1,
            'currency_id' => $currency->id,
            'price' => (int) bcmul($data['base_price'], $currency->factor),
        ]);

        DB::commit();

        return $product;
    }
}
