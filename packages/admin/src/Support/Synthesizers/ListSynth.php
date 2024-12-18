<?php

namespace Payflow\Admin\Support\Synthesizers;

use Payflow\FieldTypes\ListField;

class ListSynth extends AbstractFieldSynth
{
    public static $key = 'payflow_list_field';

    protected static $targetClass = ListField::class;

    public function dehydrate($target)
    {
        return parent::dehydrate($target); // TODO: Change the autogenerated stub
    }

    public function set(&$target, $key, $value)
    {
        $fieldValue = (array) $target->getValue();
        $fieldValue[$key] = $value;
        $target->setValue($fieldValue);
    }

    public function unset(&$target, $index)
    {
        $fieldValue = (array) $target->getValue();
        unset($fieldValue[$index]);

        $target->setValue($fieldValue);
    }
}
