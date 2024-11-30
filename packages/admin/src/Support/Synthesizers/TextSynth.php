<?php

namespace Payflow\Admin\Support\Synthesizers;

use Payflow\FieldTypes\Text;

class TextSynth extends AbstractFieldSynth
{
    public static $key = 'payflow_text_field';

    protected static $targetClass = Text::class;
}
