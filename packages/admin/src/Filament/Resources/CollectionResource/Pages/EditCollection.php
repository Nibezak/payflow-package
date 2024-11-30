<?php

namespace Payflow\Admin\Filament\Resources\CollectionResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Forms;
use Illuminate\Contracts\Support\Htmlable;
use Payflow\Admin\Filament\Resources\CollectionGroupResource;
use Payflow\Admin\Filament\Resources\CollectionResource;
use Payflow\Admin\Support\Pages\BaseEditRecord;
use Payflow\Facades\DB;
use Payflow\Models\Collection;

class EditCollection extends BaseEditRecord
{
    protected static string $resource = CollectionResource::class;

    public static bool $formActionsAreSticky = true;

    public function getTitle(): string|Htmlable
    {
        return __('payflowpanel::collection.pages.edit.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('payflowpanel::collection.pages.edit.label');
    }

    public function getBreadcrumbs(): array
    {
        return static::getResource()::getCollectionBreadcrumbs(
            $this->getRecord()
        );
    }

    protected function getDefaultHeaderActions(): array
    {
        $record = $this->getRecord();

        $successUrl = CollectionGroupResource::getUrl('edit', [
            'record' => $record->group,
        ]);

        if ($record->parent) {
            $successUrl = CollectionResource::getUrl('edit', [
                'record' => $record->parent,
            ]);
        }

        return [
            DeleteAction::make('delete')->form([
                Forms\Components\Select::make('target_collection')
                    ->model(Collection::class)
                    ->searchable()
                    ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search) use ($record): array {
                        return get_search_builder(Collection::class, $search)
                            ->get()
                            ->reject(
                                fn ($result) => $result->isDescendantOf($record)
                            )
                            ->mapWithKeys(fn (Collection $record): array => [$record->getKey() => $record->translateAttribute('name')])
                            ->all();
                    })->helperText(
                        'Choose which collection the children of this collection should be transferred to.'
                    )->hidden(
                        fn () => ! $record->children()->count()
                    ),
            ])->before(function (Collection $collection, array $data) {

                $targetId = $data['target_collection'] ?? null;

                if ($targetId) {
                    $parent = Collection::find($targetId);

                    DB::beginTransaction();
                    foreach ($collection->children as $child) {
                        $child->prependToNode($parent)->save();
                    }
                    DB::commit();

                } else {
                    $collection->descendants()->delete();
                }
            })->successRedirectUrl($successUrl),
        ];
    }
}
