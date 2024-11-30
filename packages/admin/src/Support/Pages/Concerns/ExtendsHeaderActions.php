<?php

namespace Payflow\Admin\Support\Pages\Concerns;

trait ExtendsHeaderActions
{
    protected function getDefaultHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return $this->callPayflowHook('headerActions', $this->getDefaultHeaderActions());
    }
}
