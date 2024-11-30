<?php

namespace Payflow\Admin\Support\Pages\Concerns;

use Filament\Infolists\Infolist;

trait ExtendsInfolist
{
    public function infolist(Infolist $infolist): Infolist
    {
        return self::callStaticPayflowHook('extendsInfolist', $this->getDefaultInfolist($infolist));
    }

    protected function getDefaultInfolist(Infolist $infolist): Infolist
    {
        return $infolist;
    }
}
