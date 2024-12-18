<?php

use Payflow\Admin\Filament\Resources\CustomerResource\Pages\EditCustomer;
use Payflow\Admin\Support\Facades\PayflowPanel;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('extending');

it('can extend header actions', function () {
    $class = new class extends \Payflow\Admin\Support\Extending\EditPageExtension
    {
        public function headerActions(array $actions): array
        {
            return [
                \Filament\Actions\Action::make('header_action_a'),
            ];
        }
    };

    PayflowPanel::extensions([
        EditCustomer::class => $class::class,
    ]);

    $this->asStaff(admin: true);

    $customer = \Payflow\Models\Customer::factory()->create([
        'first_name' => 'Geoff',
    ]);

    \Livewire\Livewire::test(EditCustomer::class, [
        'record' => $customer->getRouteKey(),
    ])->assertActionExists('header_action_a');
});

it('can extend form actions', function () {
    $class = new class extends \Payflow\Admin\Support\Extending\EditPageExtension
    {
        public function formActions(array $actions): array
        {
            return [
                \Filament\Actions\Action::make('form_action_a'),
            ];
        }
    };

    PayflowPanel::extensions([
        EditCustomer::class => $class::class,
    ]);

    $this->asStaff(admin: true);

    $customer = \Payflow\Models\Customer::factory()->create([
        'first_name' => 'Geoff',
    ]);

    \Livewire\Livewire::test(EditCustomer::class, [
        'record' => $customer->getRouteKey(),
    ])->assertActionExists('form_action_a');
});
