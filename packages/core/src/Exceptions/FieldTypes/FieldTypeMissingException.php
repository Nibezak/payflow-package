<?php

namespace Payflow\Exceptions\FieldTypes;

use Payflow\Exceptions\PayflowException;

class FieldTypeMissingException extends PayflowException
{
    public function __construct(string $classname)
    {
        $this->message = __('payflow::exceptions.fieldtype_missing', [
            'class' => $classname,
        ]);
    }
}
