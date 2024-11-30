<?php

namespace Payflow\Admin\Filament\Resources\OrderResource\Concerns;

use Filament\Infolists;
use Payflow\Admin\Support\Infolists\Components\Timeline;

trait DisplaysOrderTimeline
{
    public static function getTimelineInfolist(): Infolists\Components\Component
    {
        return self::callStaticPayflowHook('extendTimelineInfolist', static::getDefaultTimelineInfolist());
    }

    public static function getDefaultTimelineInfolist(): Infolists\Components\Component
    {
        return Infolists\Components\Grid::make()
            ->schema([
                Timeline::make('timeline')
                    ->label(__('payflowpanel::order.infolist.timeline.label')),
            ]);
    }
}
