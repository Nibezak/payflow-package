<?php

namespace Payflow\Admin\Support\Pages\Concerns;

trait ExtendsHeaderWidgets
{
    protected function getDefaultHeaderWidgets(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return $this->callPayflowHook('headerWidgets', $this->getDefaultHeaderWidgets());
    }
}
