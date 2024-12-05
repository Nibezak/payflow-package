<?php

namespace Payflow\Admin\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Payflow\Admin\Filament\Resources\ProductOptionResource\Pages;
use Payflow\Admin\Filament\Resources\ProductOptionResource\RelationManagers;
use Payflow\Admin\Support\Forms\Components\TranslatedText;
use Payflow\Admin\Support\Resources\BaseResource;
use Payflow\Admin\Support\Tables\Columns\TranslatedTextColumn;
use Payflow\Models\Contracts\ProductOption;
use Payflow\Models\Language;

class ProductOptionResource extends BaseResource
{
    protected static ?string $permission = 'settings';

    protected static ?string $model = ProductOption::class;

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('payflowpanel::productoption.label');
    }

    public static function getPluralLabel(): string
    {
        return __('payflowpanel::productoption.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::product-options');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('payflowpanel::global.sections.settings');
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
            static::getLabelFormComponent(),
            static::getHandleFormComponent(),
        ];
    }

    protected static function getNameFormComponent(): Component
    {
        return TranslatedText::make('name')
            ->label(__('payflowpanel::productoption.form.name.label'))
            ->required()
            ->maxLength(255)
            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                if ($operation !== 'create') {
                    return;
                }
                $set('handle', Str::slug($state[Language::getDefault()->code]));
            })
            ->live(onBlur: true)
            ->autofocus();
    }

    protected static function getLabelFormComponent(): Component
    {
        return TranslatedText::make('label')
            ->label(__('payflowpanel::productoption.form.label.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getHandleFormComponent(): Component
    {
        return Forms\Components\TextInput::make('handle')
            ->label(__('payflowpanel::productoption.form.handle.label'))
            ->required()
            ->maxLength(255)
            ->disabled(fn ($operation, $record) => $operation == 'edit' && (! $record->shared));
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns([
                TranslatedTextColumn::make('name')
                    ->label(__('payflowpanel::productoption.table.name.label')),
                TranslatedTextColumn::make('label')
                    ->label(__('payflowpanel::productoption.table.label.label')),
                Tables\Columns\TextColumn::make('handle')
                    ->label(__('payflowpanel::productoption.table.handle.label')),
                Tables\Columns\BooleanColumn::make('shared')
                    ->label(__('payflowpanel::productoption.table.shared.label')),
            ])
            ->filters([
                Tables\Filters\Filter::make('shared')
                    ->query(fn (Builder $query): Builder => $query->where('shared', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->searchable();
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ValuesRelationManager::class,
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListProductOptions::route('/'),
            'create' => Pages\CreateProductOption::route('/create'),
            'edit' => Pages\EditProductOption::route('/{record}/edit'),
        ];
    }
}
