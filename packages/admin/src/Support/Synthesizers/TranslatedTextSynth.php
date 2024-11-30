<?php

namespace Payflow\Admin\Support\Synthesizers;

use Payflow\FieldTypes\Text;
use Payflow\FieldTypes\TranslatedText;
use Payflow\Models\Language;

class TranslatedTextSynth extends AbstractFieldSynth
{
    public static $key = 'payflow_translatedtext_field';

    protected static $targetClass = TranslatedText::class;

    public function dehydrate($target)
    {
        $languages = Language::orderBy('default', 'desc')->get();

        return [
            $languages->mapWithKeys(fn ($language) => [$language->code => new Text((string) $target->getValue()->get($language->code))]
            )->toArray(),
            [],
        ];
    }

    public function hydrate($value)
    {
        $instance = new static::$targetClass;
        $instance->setValue(collect($value));

        return $instance;
    }

    public function get(&$target, $key)
    {
        return $target->{$key};
    }

    public function set(&$target, $key, $value)
    {
        $collectionValue = $target->getValue();
        $field = $collectionValue->get($key);

        $field->setValue($value);

        $collectionValue->put($key, $field);

        $target->setValue($collectionValue);
    }
}
