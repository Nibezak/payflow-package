<?php

namespace Payflow\Admin\Support\Pages\Concerns;

trait ExtendsFooterWidgets
{
    protected function getDefaultFooterWidgets(): array
    {
        return [];
    }

    protected function getFooterWidgets(): array
    {
        return $this->callPayflowHook('footerWidgets', $this->getDefaultFooterWidgets());
    }
}
