<?php

namespace Payflow\Admin\Support\Pages\Concerns;

use Illuminate\Contracts\Support\Htmlable;

trait ExtendsHeadings
{
    public function getDefaultHeading(): string
    {
        return $this->heading ?? $this->getTitle();
    }

    public function getHeading(): string|Htmlable
    {
        return $this->callPayflowHook('heading', $this->getDefaultHeading(), $this->record ?? null);
    }

    public function getDefaultSubheading(): ?string
    {
        return $this->subheading;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return $this->callPayflowHook('subHeading', $this->getDefaultSubheading(), $this->record ?? null);
    }
}
