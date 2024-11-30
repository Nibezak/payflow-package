<?php

namespace Payflow\Admin\Support\Resources\Concerns;

trait ExtendsPages
{
    public static function getPages(): array
    {
        return self::callStaticPayflowHook('extendPages', static::getDefaultPages());
    }

    protected static function getDefaultPages(): array
    {
        return [];
    }
}
