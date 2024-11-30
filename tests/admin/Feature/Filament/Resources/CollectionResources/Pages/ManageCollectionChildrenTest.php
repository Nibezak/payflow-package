<?php

use Payflow\Admin\Filament\Resources\CollectionResource\Pages\ManageCollectionChildren;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.collection');

it('can render the collection children page', function () {
    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $record = \Payflow\Models\Collection::factory()->create();

    $this->asStaff(admin: true)
        ->get(\Payflow\Admin\Filament\Resources\CollectionResource::getUrl('children', [
            'record' => $record,
        ]))
        ->assertSuccessful();
});

it('can create child categories', function () {
    $language = \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $record = \Payflow\Models\Collection::factory()->create();

    \Payflow\Models\Attribute::factory()->create([
        'name' => [
            'en' => 'Name',
        ],
        'description' => [
            'en' => 'Description',
        ],
        'handle' => 'name',
        'type' => \Payflow\FieldTypes\TranslatedText::class,
        'attribute_type' => 'collection',
    ]);

    $this->asStaff();

    expect($record->children()->count())->toBe(0);

    \Livewire\Livewire::test(ManageCollectionChildren::class, [
        'record' => $record->getKey(),
    ])->callTableAction('createChildCollection', data: [
        'name' => [$language->code => 'Test Child Category'],
    ])->assertHasNoErrors();

    expect($record->children()->count())->toBe(1);
})->group('thisone');
