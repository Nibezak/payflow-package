<?php

namespace Payflow\Admin\Support\Pages\Concerns;

trait ExtendsTabs
{
    protected function getDefaultTabs(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return $this->callPayflowHook('getTabs', $this->getDefaultTabs());
    }
}
