<?php

namespace Payflow\Admin\Support\Synthesizers;

use Payflow\FieldTypes\Vimeo;

class VimeoSynth extends AbstractFieldSynth
{
    public static $key = 'payflow_vimeo_field';

    protected static $targetClass = Vimeo::class;
}
