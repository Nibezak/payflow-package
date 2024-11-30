<?php

namespace Payflow\Admin\Filament\Resources;

use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Payflow\Admin\Filament\Resources\CurrencyResource\Pages;
use Payflow\Admin\Support\Resources\BaseResource;
use Payflow\Models\Contracts\Currency;

class CurrencyResource extends BaseResource
{
    protected static ?string $permission = 'settings:core';

    protected static ?string $model = Currency::class;

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('payflowpanel::currency.label');
    }

    public static function getPluralLabel(): string
    {
        return __('payflowpanel::currency.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::currencies');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('payflowpanel::global.sections.settings');
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
            static::getCodeFormComponent(),
            static::getExchangeRateFormComponent(),
            static::getDecimalPlacesFormComponent(),
            static::getEnabledFormComponent(),
            static::getDefaultFormComponent(),
        ];
    }

    protected static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('payflowpanel::currency.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getCodeFormComponent(): Component
    {
        return Forms\Components\TextInput::make('code')
            ->label(__('payflowpanel::currency.form.code.label'))
            ->required()
            ->unique(ignoreRecord: true)
            ->minLength(3)
            ->maxLength(3);
    }

    protected static function getExchangeRateFormComponent(): Component
    {
        return Forms\Components\TextInput::make('exchange_rate')
            ->label(__('payflowpanel::currency.form.exchange_rate.label'))
            ->numeric()
            ->required();
    }

    protected static function getDecimalPlacesFormComponent(): Component
    {
        return Forms\Components\TextInput::make('decimal_places')
            ->label(__('payflowpanel::currency.form.decimal_places.label'))
            ->numeric()
            ->required();
    }

    protected static function getEnabledFormComponent(): Component
    {
        return Forms\Components\Toggle::make('enabled')
            ->label(__('payflowpanel::currency.form.enabled.label'));
    }

    protected static function getDefaultFormComponent(): Component
    {
        return Forms\Components\Toggle::make('default')
            ->label(__('payflowpanel::currency.form.default.label'));
    }

    protected static function getDefaultTable(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            BadgeableColumn::make('name')
                ->separator('')
                ->suffixBadges([
                    Badge::make('default')
                        ->label(__('payflowpanel::currency.table.default.label'))
                        ->color('gray')
                        ->visible(fn (Model $record) => $record->default),
                ])
                ->label(__('payflowpanel::currency.table.name.label')),
            Tables\Columns\TextColumn::make('code')
                ->label(__('payflowpanel::currency.table.code.label')),
            Tables\Columns\TextColumn::make('exchange_rate')
                ->label(__('payflowpanel::currency.table.exchange_rate.label')),
            Tables\Columns\TextColumn::make('decimal_places')
                ->label(__('payflowpanel::currency.table.decimal_places.label')),
            Tables\Columns\IconColumn::make('enabled')
                ->boolean()
                ->label(__('payflowpanel::currency.table.enabled.label')),
        ]);
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListCurrencies::route('/'),
            'create' => Pages\CreateCurrency::route('/create'),
            'edit' => Pages\EditCurrency::route('/{record}/edit'),
        ];
    }
}
