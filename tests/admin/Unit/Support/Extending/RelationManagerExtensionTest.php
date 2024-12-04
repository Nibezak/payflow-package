<?php

use Payflow\Admin\Filament\Resources\CustomerResource\Pages\EditCustomer;
use Payflow\Admin\Filament\Resources\CustomerResource\RelationManagers\AddressRelationManager;
use Payflow\Admin\Filament\Resources\DiscountResource\Pages\EditDiscount;
use Payflow\Admin\Filament\Resources\DiscountResource\RelationManagers\ProductLimitationRelationManager;
use Payflow\Admin\Support\Facades\PayflowPanel;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('extending');

it('can extend table columns', function ($relationManager, $page) {
    $class = new class extends \Payflow\Admin\Support\Extending\RelationManagerExtension
    {
        public function extendTable(Filament\Tables\Table $table): Filament\Tables\Table
        {
            return $table->columns([
                ...$table->getColumns(),
                \Filament\Tables\Columns\TextColumn::make('test_column'),
            ]);
        }
    };

    PayflowPanel::extensions([
        $relationManager => $class::class,
    ]);

    $model = $page::getResource()::getModel()::factory()->create();

    \Livewire\Livewire::test($relationManager, [
        'ownerRecord' => $model,
        'pageClass' => $page,
    ])->assertTableColumnExists('test_column');
})->with([
    'AttributesRelationManager' => [AttributesRelationManager::class, EditAttributeGroup::class],
    'AddressRelationManager' => [AddressRelationManager::class, EditCustomer::class],
    'ProductLimitationRelationManager' => [ProductLimitationRelationManager::class, EditDiscount::class],
    'ValuesRelationManager' => [ValuesRelationManager::class, EditProductOption::class],
]);

it('can extend form schema', function ($relationManager, $page) {
    $class = new class extends \Payflow\Admin\Support\Extending\RelationManagerExtension
    {
        public function extendForm(Filament\Forms\Form $form): Filament\Forms\Form
        {
            $form->schema([
                ...$form->getComponents(true),
                \Filament\Forms\Components\TextInput::make('test_form_field'),
            ]);

            return $form;
        }
    };

    PayflowPanel::extensions([
        $relationManager => $class::class,
    ]);

    $model = $page::getResource()::getModel()::factory()->create();

    \Livewire\Livewire::test($relationManager, [
        'ownerRecord' => $model,
        'pageClass' => $page,
    ])->assertFormFieldExists('test_form_field');
})->with([
    'AttributesRelationManager' => [AttributesRelationManager::class, EditAttributeGroup::class],
    'ValuesRelationManager' => [ValuesRelationManager::class, EditProductOption::class],
]);
