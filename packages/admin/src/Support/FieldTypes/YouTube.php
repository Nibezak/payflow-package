<?php

namespace Payflow\Admin\Support\FieldTypes;

use Filament\Forms\Components\Component;
use Payflow\Admin\Support\Forms\Components\YouTube as YouTubeInput;
use Payflow\Admin\Support\Synthesizers\YouTubeSynth;
use Payflow\Models\Attribute;

class YouTube extends BaseFieldType
{
    protected static string $synthesizer = YouTubeSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        return YouTubeInput::make($attribute->handle)
            ->live(debounce: 200)
            ->when(filled($attribute->validation_rules), fn (YouTubeInput $component) => $component->rules($attribute->validation_rules))
            ->required((bool) $attribute->required)
            ->helperText(
                $attribute->translate('description') ?? __('payflowpanel::components.forms.youtube.helperText')
            );
    }
}
