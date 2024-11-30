<?php

namespace Payflow\Shipping\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Payflow\Admin\Support\Resources\BaseResource;
use Payflow\Shipping\Filament\Resources\ShippingExclusionListResource\Pages;
use Payflow\Shipping\Filament\Resources\ShippingExclusionListResource\RelationManagers\ShippingExclusionRelationManager;
use Payflow\Shipping\Models\Contracts\ShippingExclusionList;

class ShippingExclusionListResource extends BaseResource
{
    protected static ?string $model = ShippingExclusionList::class;

    protected static ?int $navigationSort = 1;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function getLabel(): string
    {
        return __('payflowpanel.shipping::shippingexclusionlist.label');
    }

    public static function getPluralLabel(): string
    {
        return __('payflowpanel.shipping::shippingexclusionlist.label_plural');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::shipping-exclusion-lists');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('payflowpanel.shipping::plugin.navigation.group');
    }

    public static function getDefaultForm(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema(
                static::getMainFormComponents(),
            ),
        ]);
    }

    protected static function getDefaultRelations(): array
    {
        return [
            ShippingExclusionRelationManager::class,
        ];
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
        ];
    }

    public static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('payflowpanel.shipping::shippingexclusionlist.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(
                    __('payflowpanel.shipping::shippingexclusionlist.table.name.label')
                ),
            Tables\Columns\TextColumn::make('exclusions_count')
                ->label(
                    __('payflowpanel.shipping::shippingexclusionlist.table.exclusions_count.label')
                )
                ->counts('exclusions'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingExclusionLists::route('/'),
            'edit' => Pages\EditShippingExclusionList::route('/{record}/edit'),
        ];
    }
}
