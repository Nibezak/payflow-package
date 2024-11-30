<?php

namespace Payflow\Admin\Support\Pages\Concerns;

trait ExtendsFormActions
{
    protected function getDefaultFormActions(): array
    {
        return [];
    }

    protected function getFormActions(): array
    {
        return [
            ...parent::getFormActions(),
            ...$this->callPayflowHook('formActions', $this->getDefaultFormActions()),
        ];
    }
}
