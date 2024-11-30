<?php

namespace Payflow\Admin\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Payflow\Admin\Filament\Resources\OrderResource;
use Payflow\Admin\Filament\Resources\OrderResource\Pages\ManageOrder;
use Payflow\Admin\Support\RelationManagers\BaseRelationManager;
use Payflow\Models\Order;

class OrdersRelationManager extends BaseRelationManager
{
    protected static string $relationship = 'orders';

    public function getDefaultTable(Table $table): Table
    {
        return $table->columns(
            OrderResource::getTableColumns()
        )->actions([
            Tables\Actions\Action::make('viewOrder')
                ->url(fn (Order $record): string => ManageOrder::getUrl(['record' => $record])),
        ]);
    }
}
