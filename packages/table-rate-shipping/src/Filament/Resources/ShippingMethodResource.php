<?php

namespace Payflow\Shipping\Filament\Resources;

use Awcodes\Shout\Components\Shout;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Payflow\Admin\Support\Resources\BaseResource;
use Payflow\Shipping\Filament\Resources\ShippingMethodResource\Pages;
use Payflow\Shipping\Models\Contracts\ShippingMethod;

class ShippingMethodResource extends BaseResource
{
    protected static ?string $model = ShippingMethod::class;

    protected static ?int $navigationSort = 1;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function getLabel(): string
    {
        return __('payflowpanel.shipping::shippingmethod.label');
    }

    public static function getPluralLabel(): string
    {
        return __('payflowpanel.shipping::shippingmethod.label_plural');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::shipping-methods');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('payflowpanel.shipping::plugin.navigation.group');
    }

    public static function getDefaultSubNavigation(): array
    {
        return [
            Pages\EditShippingMethod::class,
            Pages\ManageShippingMethodAvailability::class,
        ];
    }

    public static function getDefaultForm(Form $form): Form
    {
        return $form->schema([
            Shout::make('product-customer-groups')
                ->content(
                    __('payflowpanel.shipping::shippingmethod.pages.availability.customer_groups')
                )->type('warning')->hidden(function (Model $record) {
                    return $record->customerGroups()->where('enabled', true)->count();
                }),
            Forms\Components\Section::make()->schema(
                static::getMainFormComponents(),
            ),
        ])->columns(1);
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
            Forms\Components\Group::make([
                static::getCodeFormComponent(),
                static::getDriverFormComponent(),
            ])->columns(2),
            Forms\Components\Group::make([
                static::getCutoffFormComponent(),
                static::getChargeByFormComponent(),
            ])->columns(2),
            static::getStockAvailableFormComponent(),
            static::getDescriptionFormComponent(),
        ];
    }

    public static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('payflowpanel.shipping::shippingmethod.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    public static function getDescriptionFormComponent(): Component
    {
        return Forms\Components\RichEditor::make('description')
            ->label(__('payflowpanel.shipping::shippingmethod.form.description.label'));
    }

    public static function getCodeFormComponent(): Component
    {
        return Forms\Components\TextInput::make('code')
            ->label(__('payflowpanel.shipping::shippingmethod.form.code.label'))
            ->required()
            ->unique(ignoreRecord: true);
    }

    public static function getCutoffFormComponent(): Component
    {
        return Forms\Components\TimePicker::make('cutoff')
            ->label(__('payflowpanel.shipping::shippingmethod.form.cutoff.label'));
    }

    public static function getStockAvailableFormComponent(): Component
    {
        return Forms\Components\Toggle::make('stock_available')
            ->label(__('payflowpanel.shipping::shippingmethod.form.stock_available.label'));
    }

    public static function getChargeByFormComponent(): Component
    {
        return Forms\Components\Group::make([
            Forms\Components\Select::make('charge_by')
                ->label(
                    __('payflowpanel.shipping::shippingmethod.form.charge_by.label')
                )
                ->options([
                    'cart_total' => __('payflowpanel.shipping::shippingmethod.form.charge_by.options.cart_total'),
                    'weight' => __('payflowpanel.shipping::shippingmethod.form.charge_by.options.weight'),
                ]),

        ])->columns(1)->statePath('data');
    }

    public static function getDriverFormComponent(): Component
    {
        return Forms\Components\Select::make('driver')
            ->label(__('payflowpanel.shipping::shippingmethod.form.driver.label'))
            ->options([
                'ship-by' => __('payflowpanel.shipping::shippingmethod.form.driver.options.ship-by'),
                'collection' => __('payflowpanel.shipping::shippingmethod.form.driver.options.collection'),
            ])->label('Type')
            ->default('ship-by');
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

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(
                    __('payflowpanel.shipping::shippingmethod.table.name.label')
                ),
            Tables\Columns\TextColumn::make('code')
                ->label(
                    __('payflowpanel.shipping::shippingmethod.table.code.label')
                ),
            Tables\Columns\TextColumn::make('driver')
                ->label(
                    __('payflowpanel.shipping::shippingmethod.table.driver.label')
                )->formatStateUsing(
                    fn ($state) => __("payflowpanel.shipping::shippingmethod.table.driver.options.{$state}")
                ),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingMethod::route('/'),
            'edit' => Pages\EditShippingMethod::route('/{record}/edit'),
            'availability' => Pages\ManageShippingMethodAvailability::route('/{record}/availability'),
        ];
    }
}
