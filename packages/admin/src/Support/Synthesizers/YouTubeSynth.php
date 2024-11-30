<?php

namespace Payflow\Admin\Support\Synthesizers;

use Payflow\FieldTypes\YouTube;

class YouTubeSynth extends AbstractFieldSynth
{
    public static $key = 'payflow_youtube_field';

    protected static $targetClass = YouTube::class;
}
