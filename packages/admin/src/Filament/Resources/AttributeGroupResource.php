<?php

namespace Payflow\Admin\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Payflow\Admin\Filament\Resources\AttributeGroupResource\Pages;
use Payflow\Admin\Filament\Resources\AttributeGroupResource\RelationManagers;
use Payflow\Admin\Support\Resources\BaseResource;
use Payflow\Admin\Support\Tables\Columns\TranslatedTextColumn;
use Payflow\Facades\AttributeManifest;
use Payflow\Models\Contracts\AttributeGroup;
use Payflow\Models\Language;

class AttributeGroupResource extends BaseResource
{
    protected static ?string $permission = 'settings:manage-attributes';

    protected static ?string $model = AttributeGroup::class;

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('payflowpanel::attributegroup.label');
    }

    public static function getPluralLabel(): string
    {
        return __('payflowpanel::attributegroup.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::attributes');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('payflowpanel::global.sections.settings');
    }

    public static function getDefaultForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema(
                    static::getMainFormComponents()
                ),
            ]);
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getAttributableTypeFormComponent(),
            static::getNameFormComponent(),
            static::getHandleFormComponent(),
            static::getPositionFormComponent(),
        ];
    }

    protected static function getAttributableTypeFormComponent(): Component
    {
        return Forms\Components\Select::make('attributable_type')
            ->label(__('payflowpanel::attributegroup.form.attributable_type.label'))
            ->options(function () {
                return AttributeManifest::getTypes()->mapWithKeys(
                    fn ($type) => [
                        \Payflow\Facades\ModelManifest::getMorphMapKey($type) => class_basename($type),
                    ]
                );
            })
            ->required()
            ->autofocus();
    }

    protected static function getNameFormComponent(): Component
    {
        return \Payflow\Admin\Support\Forms\Components\TranslatedText::make('name')
            ->label(__('payflowpanel::attributegroup.form.name.label'))
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

    protected static function getHandleFormComponent(): Component
    {
        return Forms\Components\TextInput::make('handle')
            ->label(__('payflowpanel::attributegroup.form.handle.label'))
            ->required()
            ->maxLength(255);
    }

    protected static function getPositionFormComponent(): Component
    {
        return Forms\Components\TextInput::make('position')
            ->label(__('payflowpanel::attributegroup.form.position.label'))
            ->numeric()
            ->minValue(1)
            ->maxValue(100)
            ->required();
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('attributable_type')
                    ->label(__('payflowpanel::attributegroup.table.attributable_type.label')),
                TranslatedTextColumn::make('name')
                    ->label(__('payflowpanel::attributegroup.table.name.label')),
                Tables\Columns\TextColumn::make('handle')
                    ->label(__('payflowpanel::attributegroup.table.handle.label')),
                Tables\Columns\TextColumn::make('position')
                    ->label(__('payflowpanel::attributegroup.table.position.label'))
                    ->sortable(),
            ])
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
            ])
            ->defaultSort('position', 'asc')
            ->reorderable('position');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AttributesRelationManager::class,
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListAttributeGroups::route('/'),
            'create' => Pages\CreateAttributeGroup::route('/create'),
            'edit' => Pages\EditAttributeGroup::route('/{record}/edit'),
        ];
    }
}
