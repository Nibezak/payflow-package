<?php

namespace Payflow\Admin\Filament\Resources;

use Awcodes\Shout\Components\Shout;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Payflow\Admin\Filament\Resources\ProductResource\Pages;
use Payflow\Admin\Filament\Resources\ProductResource\RelationManagers\CustomerGroupPricingRelationManager;
use Payflow\Admin\Filament\Resources\ProductResource\RelationManagers\CustomerGroupRelationManager;
use Payflow\Admin\Filament\Widgets\Products\VariantSwitcherTable;
use Payflow\Admin\Support\Forms\Components\Attributes;
use Payflow\Admin\Support\Forms\Components\Tags as TagsComponent;
use Payflow\Admin\Support\Forms\Components\TranslatedText as TranslatedTextInput;
use Payflow\Admin\Support\RelationManagers\ChannelRelationManager;
use Payflow\Admin\Support\RelationManagers\MediaRelationManager;
use Payflow\Admin\Support\RelationManagers\PriceRelationManager;
use Payflow\Admin\Support\Resources\BaseResource;
use Payflow\Admin\Support\Tables\Columns\TranslatedTextColumn;
use Payflow\FieldTypes\Text;
use Payflow\FieldTypes\TranslatedText;
use Payflow\Models\Attribute;
use Payflow\Models\Contracts\Product;
use Payflow\Models\Currency;
use Payflow\Models\ProductVariant;
use Payflow\Models\Tag;

class ProductResource extends BaseResource
{
    protected static ?string $permission = 'catalog:manage-products';

    protected static ?string $model = Product::class;

    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?int $navigationSort = 1;

    protected static int $globalSearchResultsLimit = 5;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function getLabel(): string
    {
        return __('payflowpanel::product.label');
    }

    public static function getPluralLabel(): string
    {
        return __('payflowpanel::product.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::products');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('payflowpanel::global.sections.catalog');
    }

    public static function getDefaultSubNavigation(): array
    {
        return [
            Pages\EditProduct::class,
            Pages\ManageProductAvailability::class,
            Pages\ManageProductMedia::class,
            Pages\ManageProductPricing::class,
            Pages\ManageProductIdentifiers::class,
            Pages\ManageProductInventory::class,
            // Pages\ManageProductShipping::class,
            Pages\ManageProductUrls::class,
            Pages\ManageProductCollections::class,
            Pages\ManageProductAssociations::class,
        ];
    }



    public static function getDefaultForm(Form $form): Form
    {
        return $form
            ->schema([
                Shout::make('product-status')
                    ->content(
                        __('payflowpanel::product.status.unpublished.content')
                    )->type('info')->hidden(
                        fn (Model $record) => $record?->status == 'published'
                    ),
                Shout::make('product-customer-groups')
                    ->content(
                        __('payflowpanel::product.status.availability.customer_groups')
                    )->type('warning')->hidden(function (Model $record) {
                        return $record->customerGroups()->where('enabled', true)->count();
                    }),
                Shout::make('product-channels')
                    ->content(
                        __('payflowpanel::product.status.availability.channels')
                    )->type('warning')->hidden(function (Model $record) {
                        return $record->channels()->where('enabled', true)->count();
                    }),
                Forms\Components\Section::make()
                    ->schema(
                        static::getMainFormComponents(),
                    ),
                static::getAttributeDataFormComponent(),
            ])
            ->columns(1);
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getProductTypeFormComponent(),
            static::getTagsFormComponent(),
        ];
    }

    public static function getSkuValidation(): array
    {
        return static::callStaticPayflowHook('extendSkuValidation', [
            'required' => true,
            'unique' => true,
        ]);
    }

    public static function getSkuFormComponent(): Component
    {
        $validation = static::getSkuValidation();

        $input = Forms\Components\TextInput::make('sku')
            ->label(__('payflowpanel::product.form.sku.label'))
            ->required($validation['required'] ?? false);

        if ($validation['unique'] ?? false) {
            $input->unique(function () {
                return (new ProductVariant)->getTable();
            });
        }

        return $input;
    }

    public static function getBasePriceFormComponent(): Component
    {
        $currency = Currency::getDefault();

        return Forms\Components\TextInput::make('base_price')->numeric()->prefix(
            $currency->code
        )->rules([
            'min:'.(1 / $currency->factor),
            "decimal:0,{$currency->decimal_places}",
        ])->required();
    }

    public static function getBaseNameFormComponent(): Component
    {
        $nameType = Attribute::whereHandle('name')
            ->whereAttributeType(
                static::getModel()::morphName()
            )
            ->first()?->type ?: TranslatedText::class;

        $component = TranslatedTextInput::make('name');

        if ($nameType == Text::class) {
            $component = Forms\Components\TextInput::make('name');
        }

        return $component->label(__('payflowpanel::product.form.name.label'))->required();
    }



    public static function getProductTypeFormComponent(): Component
    {
        return Forms\Components\Select::make('product_type_id')
            ->label(__('payflowpanel::product.form.producttype.label'))
            ->relationship('productType', 'name')
            ->searchable()
            ->preload()
            ->live()
            ->required();
    }

    protected static function getTagsFormComponent(): Component
    {
        return TagsComponent::make('tags')
            ->suggestions(Tag::all()->pluck('value')->all())
            ->label(__('payflowpanel::product.form.tags.label'));
    }

    protected static function getAttributeDataFormComponent(): Component
    {
        return Attributes::make()->statePath('attribute_data');
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand', 'name'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->selectCurrentPageOnly()
            ->deferLoading();
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('status')
                ->label(__('payflowpanel::product.table.status.label'))
                ->badge()
                ->getStateUsing(
                    fn (Model $record) => $record->deleted_at ? 'deleted' : $record->status
                )
                ->formatStateUsing(fn ($state) => __('payflowpanel::product.table.status.states.'.$state))
                ->color(fn (string $state): string => match ($state) {
                    'draft' => 'warning',
                    'published' => 'success',
                    'deleted' => 'danger',
                }),
            SpatieMediaLibraryImageColumn::make('thumbnail')
                ->collection(config('payflow.media.collection'))
                ->conversion('small')
                ->limit(1)
                ->square()
                ->label(''),
            static::getNameTableColumn(),
            Tables\Columns\TextColumn::make('brand.name')
                ->label(__('payflowpanel::product.table.brand.label'))
                ->toggleable()
                ->searchable(),
            static::getSkuTableColumn(),
            Tables\Columns\TextColumn::make('variants_sum_stock')
                ->label(__('payflowpanel::product.table.stock.label'))
                ->sum('variants', 'stock'),
            Tables\Columns\TextColumn::make('productType.name')
                ->label(__('payflowpanel::product.table.producttype.label'))
                ->limit(30)
                ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                    $state = $column->getState();

                    if (strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }

                    // Only render the tooltip if the column contents exceeds the length limit.
                    return $state;
                })
                ->toggleable(),
        ];
    }

    public static function getNameTableColumn(): Tables\Columns\Column
    {
        return TranslatedTextColumn::make('attribute_data.name')
            ->attributeData()
            ->limitedTooltip()
            ->limit(50)
            ->label(__('payflowpanel::product.table.name.label'))
            ->searchable();
    }

    public static function getSkuTableColumn(): Tables\Columns\Column
    {
        return Tables\Columns\TextColumn::make('variants.sku')
            ->label(__('payflowpanel::product.table.sku.label'))
            ->tooltip(function (Tables\Columns\TextColumn $column, Model $record): ?string {

                if ($record->variants->count() <= $column->getListLimit()) {
                    return null;
                }

                if ($record->variants->count() > 30) {
                    $record->variants = $record->variants->slice(0, 30);
                }

                return $record->variants
                    ->map(fn ($variant) => $variant->sku)
                    ->implode(', ');
            })
            ->listWithLineBreaks()
            ->limitList(1)
            ->toggleable()
            ->searchable();
    }

    public static function getDefaultRelations(): array
    {
        return [
            RelationGroup::make('Availability', [
                ChannelRelationManager::class,
                CustomerGroupRelationManager::class,
            ]),
            MediaRelationManager::class,
            PriceRelationManager::class,
            CustomerGroupPricingRelationManager::class,
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'availability' => Pages\ManageProductAvailability::route('/{record}/availability'),
            'identifiers' => Pages\ManageProductIdentifiers::route('/{record}/identifiers'),
            'media' => Pages\ManageProductMedia::route('/{record}/media'),
            'pricing' => Pages\ManageProductPricing::route('/{record}/pricing'),
            'inventory' => Pages\ManageProductInventory::route('/{record}/inventory'),
            'shipping' => Pages\ManageProductShipping::route('/{record}/shipping'),
            'urls' => Pages\ManageProductUrls::route('/{record}/urls'),
            'collections' => Pages\ManageProductCollections::route('/{record}/collections'),
            'associations' => Pages\ManageProductAssociations::route('/{record}/associations'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->translateAttribute('name');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'variants.sku',
            'tags.value',
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with([
            'variants',
            'brand',
            'tags',
        ]);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('payflowpanel::product.table.sku.label') => $record->variants->first()->getIdentifier(),
            __('payflowpanel::product.table.stock.label') => $record->variants->first()->stock,
            __('payflowpanel::product.table.brand.label') => $record->brand?->name,
        ];
    }
}
