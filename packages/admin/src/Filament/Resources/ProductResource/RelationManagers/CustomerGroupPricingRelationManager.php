<?php

namespace Payflow\Admin\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Payflow\Admin\Events\ProductPricingUpdated;
use Payflow\Admin\Support\RelationManagers\BaseRelationManager;
use Payflow\Facades\DB;
use Payflow\Models\Currency;
use Payflow\Models\CustomerGroup;
use Payflow\Models\Price;

class CustomerGroupPricingRelationManager extends BaseRelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'prices';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('payflowpanel::relationmanagers.customer_group_pricing.title');
    }

    protected function getTableHeading(): string|Htmlable|null
    {
        return __('payflowpanel::relationmanagers.customer_group_pricing.table.heading');
    }

    public function getDefaultForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Select::make('currency_id')
                        ->label(
                            __('payflowpanel::relationmanagers.pricing.form.currency_id.label')
                        )->relationship(name: 'currency', titleAttribute: 'name')
                        ->default(function () {
                            return Currency::getDefault()?->id;
                        })
                        ->helperText(
                            __('payflowpanel::relationmanagers.pricing.form.currency_id.helper_text')
                        )->required(),
                    Forms\Components\Select::make('customer_group_id')
                        ->label(
                            __('payflowpanel::relationmanagers.pricing.form.customer_group_id.label')
                        )->helperText(
                            __('payflowpanel::relationmanagers.pricing.form.customer_group_id.helper_text')
                        )->relationship(name: 'customerGroup', titleAttribute: 'name')
                        ->required()
                        ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, Forms\Get $get) {
                            $owner = $this->getOwnerRecord();

                            return $rule
                                ->when(blank($get('customer_group_id')),
                                    fn (Unique $rule) => $rule->whereNull('customer_group_id'),
                                    fn (Unique $rule) => $rule->where('customer_group_id', $get('customer_group_id')))
                                ->where('min_quantity', 1)
                                ->where('currency_id', $get('currency_id'))
                                ->where('priceable_type', $owner->getMorphClass())
                                ->where('priceable_id', $owner->id);
                        }),
                ])->columns(2),

                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('price')->formatStateUsing(
                        fn ($state) => $state?->decimal(rounding: false)
                    )->numeric()->unique(
                        modifyRuleUsing: function (Unique $rule, Forms\Get $get) {
                            $owner = $this->getOwnerRecord();

                            return $rule
                                ->when(blank($get('customer_group_id')),
                                    fn (Unique $rule) => $rule->whereNull('customer_group_id'),
                                    fn (Unique $rule) => $rule->where('customer_group_id', $get('customer_group_id')))
                                ->where('min_quantity', 1)
                                ->where('currency_id', $get('currency_id'))
                                ->where('priceable_type', $owner->getMorphClass())
                                ->where('priceable_id', $owner->id);
                        }
                    )->helperText(
                        __('payflowpanel::relationmanagers.pricing.form.price.helper_text')
                    )->required(),
                    Forms\Components\TextInput::make('compare_price')->formatStateUsing(
                        fn ($state) => $state?->decimal(rounding: false)
                    )->label(
                        __('payflowpanel::relationmanagers.pricing.form.compare_price.label')
                    )->helperText(
                        __('payflowpanel::relationmanagers.pricing.form.compare_price.helper_text')
                    )->numeric(),
                ])->columns(2),
            ])->columns(1);
    }

    public function getDefaultTable(Table $table): Table
    {
        $priceTable = (new Price)->getTable();
        $cgTable = CustomerGroup::query()->select([DB::raw('id as cg_id'), 'name']);

        return $table
            ->recordTitleAttribute('name')
            ->description(
                __('payflowpanel::relationmanagers.customer_group_pricing.table.description')
            )
            ->modifyQueryUsing(
                fn ($query) => $query
                    ->leftJoinSub($cgTable, 'cg', fn ($join) => $join->on('customer_group_id', 'cg.cg_id'))
                    ->where("{$priceTable}.min_quantity", 1)
                    ->whereNotNull("{$priceTable}.customer_group_id")
            )
            ->defaultSort(fn ($query) => $query->orderBy('cg.name')->orderBy('min_quantity'))
            ->emptyStateHeading(
                __('payflowpanel::relationmanagers.customer_group_pricing.table.empty_state.label')
            )
            ->emptyStateDescription(__('payflowpanel::relationmanagers.customer_group_pricing.table.empty_state.description'))
            ->columns([
                Tables\Columns\TextColumn::make('price')
                    ->label(
                        __('payflowpanel::relationmanagers.pricing.table.price.label')
                    )->formatStateUsing(
                        fn ($state) => $state->formatted,
                    )->sortable(),
                Tables\Columns\TextColumn::make('currency.code')->label(
                    __('payflowpanel::relationmanagers.pricing.table.currency.label')
                )->sortable(),
                Tables\Columns\TextColumn::make('customerGroup.name')->label(
                    __('payflowpanel::relationmanagers.pricing.table.customer_group.label')
                )->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('currency')
                    ->relationship(name: 'currency', titleAttribute: 'name')
                    ->preload()
                    ->label(
                        __('payflowpanel::relationmanagers.pricing.table.currency.label')
                    ),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->mutateFormDataUsing(function (array $data) {
                    $currencyModel = Currency::find($data['currency_id']);

                    $data['min_quantity'] = 1;
                    $data['price'] = (int) ($data['price'] * $currencyModel->factor);

                    return $data;
                })->label(
                    __('payflowpanel::relationmanagers.customer_group_pricing.table.actions.create.label')
                )->modalHeading(__('payflowpanel::relationmanagers.customer_group_pricing.table.actions.create.modal.heading'))
                    ->after(
                        fn () => ProductPricingUpdated::dispatch($this->getOwnerRecord())
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->mutateFormDataUsing(function (array $data): array {
                    $currencyModel = Currency::find($data['currency_id']);

                    $data['min_quantity'] = 1;
                    $data['price'] = (int) ($data['price'] * $currencyModel->factor);

                    return $data;
                })->after(
                    fn () => ProductPricingUpdated::dispatch($this->getOwnerRecord())
                ),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
