<?php

namespace Payflow\Admin\Support\Synthesizers;

use Payflow\FieldTypes\Number;

class NumberSynth extends AbstractFieldSynth
{
    public static $key = 'payflow_number_field';

    protected static $targetClass = Number::class;
}
