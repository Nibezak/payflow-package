<?php

use Payflow\Admin\Filament\Resources\ActivityResource;
use Payflow\Admin\Filament\Resources\ActivityResource\Pages\ListActivities;
use Payflow\Admin\Filament\Resources\AttributeGroupResource;
use Payflow\Admin\Filament\Resources\AttributeGroupResource\Pages\ListAttributeGroups;
use Payflow\Admin\Filament\Resources\CurrencyResource;
use Payflow\Admin\Filament\Resources\CurrencyResource\Pages\ListCurrencies;
use Payflow\Admin\Filament\Resources\CustomerResource;
use Payflow\Admin\Filament\Resources\LanguageResource;
use Payflow\Admin\Filament\Resources\LanguageResource\Pages\ListLanguages;
use Payflow\Admin\Support\Extending\ResourceExtension;
use Payflow\Admin\Support\Facades\PayflowPanel;
use Payflow\Tests\Admin\Stubs\Filament\TestCustomerAddressRelationManager;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('extending', 'extending.resources');

it('can extend relationship managers', function () {
    $class = new class extends ResourceExtension
    {
        public function getRelations(array $managers): array
        {
            return [
                TestCustomerAddressRelationManager::class,
            ];
        }
    };

    PayflowPanel::extensions([
        CustomerResource::class => $class::class,
    ]);

    $relations = CustomerResource::getRelations();
    expect($relations)->toContain(TestCustomerAddressRelationManager::class);
});

it('can extend table columns', function ($resource, $page) {
    $class = new class extends ResourceExtension
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
        $resource => $class::class,
    ]);

    $this->asStaff();

    \Livewire\Livewire::test($page)->assertTableColumnExists('test_column');
})->with([
    'ListCurrencies' => [CurrencyResource::class, ListCurrencies::class],
    'ListLanguages' => [LanguageResource::class, ListLanguages::class],
    'ListActivities' => [ActivityResource::class, ListActivities::class],
    'ListAttributeGroups' => [AttributeGroupResource::class, ListAttributeGroups::class],
]);

it('can extend form schema', function ($resource, $page) {
    $class = new class extends \Payflow\Admin\Support\Extending\ResourceExtension
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
        $resource => $class::class,
    ]);

    $this->asStaff(admin: true);

    $model = $resource::getModel()::factory()->create();

    \Livewire\Livewire::test($page, [
        'record' => $model->getRouteKey(),
    ])->assertFormFieldExists('test_form_field');
})->with([
    'CurrencyResource' => [CurrencyResource::class, CurrencyResource\Pages\EditCurrency::class],
    'LanguageResource' => [LanguageResource::class, LanguageResource\Pages\EditLanguage::class],
]);
