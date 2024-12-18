<?php

namespace Payflow\Admin\Support;

class OrderStatus
{
    protected static array $cachedStatusColor = [];

    protected static array $cachedStatusLabel = [];

    public static function getLabel($status): string
    {
        return static::$cachedStatusLabel[$status] ??= filled($label = config('payflow.orders.statuses.'.$status.'.label')) ? $label : (filled($status) ? $status : 'N/A');
    }

    public static function getColor($status): array
    {
        return static::$cachedStatusColor[$status] ??= \Filament\Support\Colors\Color::hex(filled($color = config('payflow.orders.statuses.'.$status.'.color')) ? $color : '#7C7C7C');
    }
}
