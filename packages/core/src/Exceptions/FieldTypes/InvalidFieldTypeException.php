<?php

namespace Payflow\Exceptions\FieldTypes;

use Payflow\Exceptions\PayflowException;

class InvalidFieldTypeException extends PayflowException
{
    public function __construct(string $classname)
    {
        $this->message = __('payflow::exceptions.invalid_fieldtype', [
            'class' => $classname,
        ]);
    }
}
