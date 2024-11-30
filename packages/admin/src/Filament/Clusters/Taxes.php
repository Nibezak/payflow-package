<?php

namespace Payflow\Admin\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Support\Facades\FilamentIcon;

class Taxes extends Cluster
{
    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __('payflowpanel::global.sections.settings');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::tax');
    }
}
