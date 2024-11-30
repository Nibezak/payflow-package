<?php

use Illuminate\Database\Eloquent\Model;
use Payflow\Admin\Filament\Resources\ProductResource\Pages\ManageProductMedia;
use Payflow\Admin\Support\Facades\PayflowPanel;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('extending');

it('can customise page headings', function () {
    $class = new class extends \Payflow\Admin\Support\Extending\RelationPageExtension
    {
        public function heading($title, Model $record): string
        {
            return 'New Heading';
        }

        public function subheading($title, Model $record): ?string
        {
            return 'New Subheading';
        }
    };

    \Payflow\Models\Language::factory()->create();
    $product = \Payflow\Models\Product::factory()->create();

    PayflowPanel::extensions([
        ManageProductMedia::class => $class::class,
    ]);

    $this->asStaff(admin: true);

    \Livewire\Livewire::test(ManageProductMedia::class, [
        'record' => $product->getRouteKey(),
    ])
        ->assertSee('New Heading')
        ->assertSee('New Subheading');
});
