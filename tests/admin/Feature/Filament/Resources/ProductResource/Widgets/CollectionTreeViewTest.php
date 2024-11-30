<?php

use Payflow\Admin\Filament\Resources\CollectionGroupResource\Widgets\CollectionTreeView;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.product.widgets');

it('can mount widget', function () {
    $group = \Payflow\Models\CollectionGroup::factory()->create();

    \Livewire\Livewire::test(CollectionTreeView::class, [
        'record' => $group,
    ])->assertHasNoErrors();
});

it('can render collection tree', function () {
    $group = \Payflow\Models\CollectionGroup::factory()->create();

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $collection = \Payflow\Models\Collection::factory(1)->create([
        'collection_group_id' => $group->id,
    ]);

    \Livewire\Livewire::test(CollectionTreeView::class, [
        'record' => $group,
    ])->assertSet('nodes', CollectionTreeView::mapCollections(
        collect($collection)
    ))->assertHasNoErrors();
});

it('can create root collection', function () {
    $group = \Payflow\Models\CollectionGroup::factory()->create();

    \Payflow\Models\Attribute::factory()->create([
        'handle' => 'name',
        'type' => \Payflow\FieldTypes\TranslatedText::class,
        'attribute_type' => 'collection',
    ]);

    $language = \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    \Livewire\Livewire::test(CollectionTreeView::class, [
        'record' => $group,
    ])->callAction('createRootCollection', [
        'name' => [$language->code => 'Foo Bar'],
    ])->assertSet('nodes.0.name', 'Foo Bar')
        ->assertHasNoErrors();
});

it('can toggle collection children', function () {
    $group = \Payflow\Models\CollectionGroup::factory()->create();

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $collection = \Payflow\Models\Collection::factory()->create([
        'collection_group_id' => $group->id,
    ]);

    \Payflow\Models\Collection::factory(2)->create([
        'collection_group_id' => $group->id,
    ])->each(
        fn ($child) => $collection->prependNode($child)
    );

    \Livewire\Livewire::test(CollectionTreeView::class, [
        'record' => $group,
    ])->assertSet('nodes.0.children', [])
        ->call('toggleChildren', $collection->id)
        ->assertSet('nodes.0.children', CollectionTreeView::mapCollections(
            $collection->children()->defaultOrder()->get()
        ))
        ->call('toggleChildren', $collection->id)
        ->assertSet('nodes.0.children', [])
        ->assertHasNoErrors();
});

it('can create child collection', function () {
    $group = \Payflow\Models\CollectionGroup::factory()->create();

    \Payflow\Models\Attribute::factory()->create([
        'handle' => 'name',
        'type' => \Payflow\FieldTypes\TranslatedText::class,
        'attribute_type' => 'collection',
    ]);

    $language = \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $collection = \Payflow\Models\Collection::factory()->create([
        'collection_group_id' => $group->id,
    ]);

    $child = \Payflow\Models\Collection::factory()->create([
        'collection_group_id' => $group->id,
    ]);

    $collection->prependNode($child);

    \Livewire\Livewire::test(CollectionTreeView::class, [
        'record' => $group,
    ])->callAction('addChildCollection', [
        'name' => [$language->code => 'Sub Collection'],
    ], ['id' => $collection->id])
        ->assertCount('nodes', 1)
        ->assertSet('nodes.0.children.0.id', $child->id)
        ->callAction('makeRoot', arguments: ['id' => $child->id])
        ->assertCount('nodes.0.children', 0)
        ->assertCount('nodes', 2);
});

it('can set child collection as root', function () {
    $group = \Payflow\Models\CollectionGroup::factory()->create();

    \Payflow\Models\Attribute::factory()->create([
        'handle' => 'name',
        'type' => \Payflow\FieldTypes\TranslatedText::class,
        'attribute_type' => 'collection',
    ]);

    $language = \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $collection = \Payflow\Models\Collection::factory()->create([
        'collection_group_id' => $group->id,
    ]);

    \Livewire\Livewire::test(CollectionTreeView::class, [
        'record' => $group,
    ])->callAction('addChildCollection', [
        'name' => [$language->code => 'Sub Collection'],
    ], ['id' => $collection->id])
        ->assertSet('nodes.0.children.0.name', 'Sub Collection');
});

it('can reorder collections', function () {
    $group = \Payflow\Models\CollectionGroup::factory()->create();

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $collectionA = \Payflow\Models\Collection::factory()->create([
        'collection_group_id' => $group->id,
    ]);

    $collectionB = \Payflow\Models\Collection::factory()->create([
        'collection_group_id' => $group->id,
    ]);

    \Livewire\Livewire::test(CollectionTreeView::class, [
        'record' => $group,
    ])->assertSet('nodes.0.id', $collectionA->id)
        ->assertSet('nodes.1.id', $collectionB->id)
        ->call('sort', $collectionA->id, $collectionB->id, 'after')
        ->assertSet('nodes.0.id', $collectionB->id)
        ->assertSet('nodes.1.id', $collectionA->id);
});
