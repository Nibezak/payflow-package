<?php

namespace Payflow\Admin\Support\Synthesizers;

use Payflow\FieldTypes\Dropdown;

class DropdownSynth extends AbstractFieldSynth
{
    public static $key = 'payflow_dropdown_field';

    protected static $targetClass = Dropdown::class;
}
