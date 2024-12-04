<?php
namespace Payflow\Admin\Filament\Pages;

use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\OrderStatsOverview;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\OrderTotalsChart;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\NewVsReturningCustomersChart;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\PopularProductsTable;
use Payflow\Admin\Support\Pages\BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            new WidgetConfiguration(OrderStatsOverview::class, [
                'columnSpan' => 12,  // Full width
            ]),
            new WidgetConfiguration(OrderTotalsChart::class, [
                'columnSpan' => 12,  // Full width
            ]),
            new WidgetConfiguration(NewVsReturningCustomersChart::class, [
                'columnSpan' => 12,  // Full width
            ]),
            new WidgetConfiguration(PopularProductsTable::class, [
                'columnSpan' => 12,  // Full width
            ]),
        ];
    }

    public static function getNavigationIcon(): ?string
    {
        // Use a valid Filament icon string
        return 'heroicon-o-home';  // Example of a valid icon
    }
}
