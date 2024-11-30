<?php

namespace Payflow\Admin\Filament\Resources\AttributeGroupResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Payflow\Admin\Support\Facades\AttributeData;
use Payflow\Admin\Support\Forms\Components\TranslatedText;
use Payflow\Admin\Support\RelationManagers\BaseRelationManager;
use Payflow\Admin\Support\Tables\Columns\TranslatedTextColumn;
use Payflow\Models\Language;

class AttributesRelationManager extends BaseRelationManager
{
    protected static string $relationship = 'attributes';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('payflowpanel::attribute.plural_label');
    }

    protected static ?string $recordTitleAttribute = 'name.en';  // TODO: localise somehow

    public function getDefaultForm(Form $form): Form
    {
        return $form
            ->schema([
                TranslatedText::make('name')
                    ->label(
                        __('payflowpanel::attribute.form.name.label')
                    )
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $set('handle', Str::slug($state[Language::getDefault()->code]));
                    }),
                TranslatedText::make('description')
                    ->label(
                        __('payflowpanel::attribute.form.description.label')
                    )
                    ->helperText(
                        __('payflowpanel::attribute.form.description.helper')
                    )
                    ->afterStateHydrated(fn ($state, $component) => $state ?: $component->state([Language::getDefault()->code => null]))
                    ->maxLength(255),
                Forms\Components\TextInput::make('handle')
                    ->label(
                        __('payflowpanel::attribute.form.handle.label')
                    )->dehydrated()
                    ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, RelationManager $livewire) {
                        return $rule->where('attribute_group_id', $livewire->ownerRecord->id);
                    })->disabled(
                        fn (?Model $record) => (bool) $record
                    )
                    ->required(),
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Toggle::make('searchable')
                        ->label(
                            __('payflowpanel::attribute.form.searchable.label')
                        )->default(false),
                    Forms\Components\Toggle::make('filterable')
                        ->label(
                            __('payflowpanel::attribute.form.filterable.label')
                        )->default(false),
                    Forms\Components\Toggle::make('required')
                        ->label(
                            __('payflowpanel::attribute.form.required.label')
                        )->default(false),
                ]),
                Forms\Components\Select::make('type')->label(
                    __('payflowpanel::attribute.form.type.label')
                )->disabled(
                    fn (?Model $record) => (bool) $record
                )->options(
                    AttributeData::getFieldTypes()->mapWithKeys(function ($fieldType) {
                        $langKey = strtolower(
                            class_basename($fieldType)
                        );

                        return [
                            $fieldType => __("payflowpanel::fieldtypes.{$langKey}.label"),
                        ];
                    })->toArray()
                )->required()->live()->afterStateUpdated(fn (Forms\Components\Select $component) => $component
                    ->getContainer()
                    ->getComponent('configuration')
                    ->getChildComponentContainer()

                    ->fill()),
                Forms\Components\TextInput::make('validation_rules')->label(
                    __('payflowpanel::attribute.form.validation_rules.label')
                )
                    ->string()
                    ->nullable()
                    ->helperText(
                        __('payflowpanel::attribute.form.validation_rules.helper')
                    ),
                Forms\Components\Grid::make(1)
                    ->schema(function (Forms\Get $get) {
                        return AttributeData::getConfigurationFields($get('type'));
                    })->key('configuration')->statePath('configuration'),
            ]);
    }

    public function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns([
                TranslatedTextColumn::make('name')->label(
                    __('payflowpanel::attribute.table.name.label')
                ),
                Tables\Columns\TextColumn::make('description.en')->label(
                    __('payflowpanel::attribute.table.description.label')
                ),
                Tables\Columns\TextColumn::make('handle')
                    ->label(
                        __('payflowpanel::attribute.table.handle.label')
                    ),
                Tables\Columns\TextColumn::make('type')->label(
                    __('payflowpanel::attribute.table.type.label')
                ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->mutateFormDataUsing(function (array $data, RelationManager $livewire) {
                    $data['configuration'] = $data['configuration'] ?? [];
                    $data['system'] = false;
                    $data['attribute_type'] = $livewire->ownerRecord->attributable_type;
                    $data['position'] = $livewire->ownerRecord->attributes()->count() + 1;

                    return $data;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('position', 'asc')
            ->reorderable('position');
    }
}
