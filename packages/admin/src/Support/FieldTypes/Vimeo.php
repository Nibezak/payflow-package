<?php

namespace Payflow\Admin\Support\FieldTypes;

use Filament\Forms\Components\Component;
use Payflow\Admin\Support\Forms\Components\Vimeo as VimeoInput;
use Payflow\Admin\Support\Synthesizers\VimeoSynth;
use Payflow\Models\Attribute;

class Vimeo extends BaseFieldType
{
    protected static string $synthesizer = VimeoSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        return VimeoInput::make($attribute->handle)
            ->live(debounce: 200)
            ->when(filled($attribute->validation_rules), fn (VimeoInput $component) => $component->rules($attribute->validation_rules))
            ->required((bool) $attribute->required)
            ->helperText(
                $attribute->translate('description') ?? __('payflowpanel::components.forms.youtube.helperText')
            );
    }
}
