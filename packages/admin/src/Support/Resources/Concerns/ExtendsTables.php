<?php

namespace Payflow\Admin\Support\Resources\Concerns;

use Filament\Tables\Table;

trait ExtendsTables
{
    public static function table(Table $table): Table
    {
        return self::callStaticPayflowHook('extendTable', static::getDefaultTable($table));
    }

    protected static function getDefaultTable(Table $table): Table
    {
        return $table;
    }
}
