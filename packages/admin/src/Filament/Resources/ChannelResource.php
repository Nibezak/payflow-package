<?php

namespace Payflow\Admin\Filament\Resources;

use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Payflow\Admin\Filament\Resources\ChannelResource\Pages;
use Payflow\Admin\Support\Resources\BaseResource;
use Payflow\Models\Contracts\Channel;

class ChannelResource extends BaseResource
{
    protected static ?string $permission = 'settings:core';

    protected static ?string $model = Channel::class;

    protected static ?int $navigationSort = 1;
    
    protected static bool $shouldRegisterNavigation = false;


    public static function getLabel(): string
    {
        return __('payflowpanel::channel.label');
    }

    public static function getPluralLabel(): string
    {
        return __('payflowpanel::channel.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::channels');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('payflowpanel::global.sections.settings');
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
            static::getHandleFormComponent(),
            static::getUrlFormComponent(),
            static::getDefaultFormComponent(),
        ];
    }

    protected static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('payflowpanel::channel.form.name.label'))
            ->required()
            ->maxLength(255)
            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                if ($operation !== 'create') {
                    return;
                }
                $set('handle', Str::slug($state));
            })
            ->live(onBlur: true)
            ->autofocus();
    }

    protected static function getHandleFormComponent(): Component
    {
        return Forms\Components\TextInput::make('handle')
            ->label(__('payflowpanel::channel.form.handle.label'))
            ->required()
            ->unique(ignoreRecord: true)
            ->minLength(3)
            ->maxLength(255);
    }

    protected static function getUrlFormComponent(): Component
    {
        return Forms\Components\TextInput::make('url')
            ->label(__('payflowpanel::channel.form.url.label'))
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getDefaultFormComponent(): Component
    {
        return Forms\Components\Toggle::make('default')
            ->label(__('payflowpanel::channel.form.default.label'));
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
            BadgeableColumn::make('name')
                ->separator('')
                ->suffixBadges([
                    Badge::make('default')
                        ->label(__('payflowpanel::channel.table.default.label'))
                        ->color('gray')
                        ->visible(fn (Model $record) => $record->default),
                ])
                ->label(__('payflowpanel::channel.table.name.label')),
            Tables\Columns\TextColumn::make('handle')
                ->label(__('payflowpanel::channel.table.handle.label')),
            Tables\Columns\TextColumn::make('url')
                ->label(__('payflowpanel::channel.table.url.label')),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListChannels::route('/'),
            'create' => Pages\CreateChannel::route('/create'),
            'edit' => Pages\EditChannel::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
