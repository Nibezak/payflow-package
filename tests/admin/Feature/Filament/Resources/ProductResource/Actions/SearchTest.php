<?php

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.product.search');

it('can search product by name on list', function () {

    $this->asStaff(admin: true);

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    \Payflow\Models\Currency::factory()->create([
        'default' => true,
    ]);

    $products = \Payflow\Models\Product::factory()->count(2)->create();

    $products->each(function ($product) {
        \Payflow\Models\ProductVariant::factory()->create([
            'product_id' => $product->id,
        ]);
    });

    $name = $products->first()->translateAttribute('name');

    $products = $products->filter(function ($item, $key) use ($name) {
        return $name == $item->translateAttribute('name');
    });

    \Livewire\Livewire::test(Payflow\Admin\Filament\Resources\ProductResource\Pages\ListProducts::class)
        ->searchTable($name)
        ->assertCanNotSeeTableRecords($products);
});
