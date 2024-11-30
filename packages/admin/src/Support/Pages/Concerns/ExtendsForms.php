<?php

namespace Payflow\Admin\Support\Pages\Concerns;

use Filament\Forms\Form;

trait ExtendsForms
{
    public function form(Form $form): Form
    {
        return self::callPayflowHook('extendForm', $this->getDefaultForm($form));
    }

    public function getDefaultForm(Form $form): Form
    {
        return $form;
    }
}
