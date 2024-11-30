<?php

namespace Payflow\Admin\Filament\Resources\OrderResource\Pages;

use Filament\Resources\Components\Tab;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Payflow\Admin\Filament\Resources\OrderResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListOrders extends BaseListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getDefaultTabs(): array
    {
        $statuses = collect(
            config('payflow.orders.statuses', [])
        )->filter(
            fn ($config) => $config['favourite'] ?? false
        );

        return [
            'all' => Tab::make('All'),
            ...collect($statuses)->mapWithKeys(
                fn ($config, $status) => [
                    $status => Tab::make($config['label'])
                        ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $status)),
                ]
            ),
        ];
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
