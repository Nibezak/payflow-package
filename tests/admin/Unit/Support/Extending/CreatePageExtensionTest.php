<?php

use Payflow\Admin\Filament\Resources\ChannelResource;
use Payflow\Admin\Support\Facades\PayflowPanel;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('extending');

it('can extend header actions', function () {
    $class = new class extends \Payflow\Admin\Support\Extending\CreatePageExtension
    {
        public function headerActions(array $actions): array
        {
            return [
                \Filament\Actions\Action::make('header_action_a'),
            ];
        }
    };

    PayflowPanel::extensions([
        ChannelResource\Pages\CreateChannel::class => $class::class,
    ]);

    $this->asStaff(admin: true);

    \Livewire\Livewire::test(ChannelResource\Pages\CreateChannel::class)
        ->assertActionExists('header_action_a');
});

it('can extend form actions', function () {
    $class = new class extends \Payflow\Admin\Support\Extending\CreatePageExtension
    {
        public function formActions(array $actions): array
        {
            return [
                \Filament\Actions\Action::make('form_action_a'),
            ];
        }
    };

    PayflowPanel::extensions([
        ChannelResource\Pages\CreateChannel::class => $class::class,
    ]);

    $this->asStaff(admin: true);

    \Livewire\Livewire::test(ChannelResource\Pages\CreateChannel::class)
        ->assertActionExists('form_action_a');
});
