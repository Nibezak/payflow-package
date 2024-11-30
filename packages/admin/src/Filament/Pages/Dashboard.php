<?php

namespace Payflow\Admin\Filament\Pages;

use Filament\Support\Facades\FilamentIcon;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\AverageOrderValueChart;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\LatestOrdersTable;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\NewVsReturningCustomersChart;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\OrdersSalesChart;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\OrderStatsOverview;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\OrderTotalsChart;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\PopularProductsTable;
use Payflow\Admin\Support\Pages\BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            OrderStatsOverview::class,
            OrderTotalsChart::class,
            OrdersSalesChart::class,
            AverageOrderValueChart::class,
            NewVsReturningCustomersChart::class,
            PopularProductsTable::class,
            LatestOrdersTable::class,
        ];
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::dashboard');
    }
}
