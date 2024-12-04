<?php

namespace Payflow\Admin\Filament\Widgets\Dashboard\Orders;

use Carbon\CarbonPeriod;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Payflow\Facades\DB;
use Payflow\Models\Currency;
use Payflow\Models\Order;

class OrderTotalsChart extends ApexChartWidget
{
    use InteractsWithPageFilters;

    /**
     * Chart Id
     */
    protected static ?string $chartId = 'orderTotalsChart';

    protected static ?string $pollingInterval = '60s';

    protected function getHeading(): ?string
    {
        return __('payflowpanel::widgets.dashboard.orders.order_totals_chart.heading');
    }

    protected function getOrderQuery(?\DateTime $from = null, ?\DateTime $to = null)
    {
        return Order::whereNotNull('placed_at')
            ->whereBetween('placed_at', [
                $from,
                $to,
            ]);
    }

    protected function getOptions(): array
    {
        $datasets = [];
        $labels = [];
        $currency = Currency::getDefault();

        $date = now()->settings([
            'monthOverflow' => false,
        ]);

        $from = $date->clone()->subYear();

        $currentPeriod = $this->getTotalsForPeriod($from, $date);
        $previousPeriod = $this->getTotalsForPeriod($from->clone()->subYear(), $date->clone()->subYear());

        return [
            'chart' => [
                'type' => 'area',
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'series' => [
                [
                    'name' => __('payflowpanel::widgets.dashboard.orders.order_totals_chart.series_one.label'),
                    'data' => $currentPeriod->pluck('sub_total'),
                ],
                [
                    'name' => __('payflowpanel::widgets.dashboard.orders.order_totals_chart.series_two.label'),
                    'data' => $previousPeriod->pluck('sub_total'),
                ],
            ],
            'xaxis' => [
                'categories' => $previousPeriod->map(
                    fn ($record) => "{$record->month}"
                ),
            ],
            'yaxis' => [
                'title' => [
                    'text' => __('payflowpanel::widgets.dashboard.orders.order_totals_chart.yaxis.label', [
                        'currency' => $currency->code,
                    ]),
                ],
            ],
            'tooltip' => [
                'x' => [
                    'format' => 'd M yyyy',
                ],
            ],
        ];
    }

    protected function getTotalsForPeriod($from, $to)
    {
        $currentPeriod = collect();
        $period = CarbonPeriod::create($from, '1 month', $to);

        $results = $this->getOrderQuery($from, $to)
            ->select(
                DB::RAW('SUM(total) as total'),
                DB::RAW('SUM(shipping_total) as shipping_total'),
                DB::RAW('SUM(discount_total) as discount_total'),
                DB::RAW('SUM(sub_total) as sub_total'),
                DB::RAW('SUM(tax_total) as tax_total'),
                DB::RAW(db_date('placed_at', '%M', 'month')),
                DB::RAW(db_date('placed_at', '%Y', 'year')),
                DB::RAW(db_date('placed_at', '%Y%m', 'monthstamp'))
            )->groupBy(
                DB::RAW('month'),
                DB::RAW('year'),
                DB::RAW('monthstamp'),
                DB::RAW(db_date('placed_at', '%Y-%m')),
            )->orderBy(DB::RAW(db_date('placed_at', '%Y-%m')), 'desc')->get();

        foreach ($period as $date) {
            // Find our records for this period.
            $report = $results->first(function ($month) use ($date) {
                return $month->monthstamp == $date->format('Ym');
            });
            $currentPeriod->push((object) [
                'order_total' => $report?->total->decimal ?: 0,
                'shipping_total' => $report?->shipping_total->decimal ?: 0,
                'discount_total' => $report?->discount_total->decimal ?: 0,
                'sub_total' => $report?->sub_total->decimal ?: 0,
                'month' => $date->format('F'),
                'year' => $date->format('Y'),
                'tax_total' => $report?->tax_total->decimal ?: 0,
            ]);
        }

        return $currentPeriod;
    }
}
