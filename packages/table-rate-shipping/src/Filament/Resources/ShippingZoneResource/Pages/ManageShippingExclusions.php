<?php

namespace Payflow\Shipping\Filament\Resources\ShippingZoneResource\Pages;

use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Payflow\Shipping\Filament\Resources\ShippingExclusionListResource;
use Payflow\Shipping\Filament\Resources\ShippingZoneResource;

class ManageShippingExclusions extends ManageRelatedRecords
{
    protected static string $resource = ShippingZoneResource::class;

    protected static string $relationship = 'shippingExclusions';

    protected static ?string $recordTitle = 'name';

    public function getTitle(): string|Htmlable
    {
        return __('payflowpanel.shipping::relationmanagers.exclusions.title_plural');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::shipping-exclusion-lists');
    }

    public static function getNavigationLabel(): string
    {
        return __('payflowpanel.shipping::relationmanagers.exclusions.title_plural');
    }

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table->columns(
            ShippingExclusionListResource::getTableColumns(),
        )->headerActions([
            Tables\Actions\AttachAction::make()
                ->color('primary')
                ->label(
                    __('payflowpanel.shipping::relationmanagers.exclusions.actions.attach.label')
                )
                ->preloadRecordSelect()
                ->recordTitleAttribute('name'),
        ])->actions([
            Tables\Actions\DetachAction::make('detach')
                ->label(
                    __('payflowpanel.shipping::relationmanagers.exclusions.actions.detach.label')
                ),

        ]);
    }
}
