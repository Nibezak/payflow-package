<?php

namespace Payflow\Admin\Support\ActivityLog\Concerns;

use Payflow\Admin\Livewire\Components\ActivityLogFeed;

trait CanDispatchActivityUpdated
{
    protected function dispatchActivityUpdated(): bool
    {
        $this->dispatch(ActivityLogFeed::UPDATED)->to(ActivityLogFeed::class);

        return true;
    }
}
