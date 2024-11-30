<?php

namespace Payflow\Exceptions;

class NonPurchasableItemException extends PayflowException
{
    public function __construct(string $classname)
    {
        $this->message = __('payflow::exceptions.non_purchasable_item', [
            'class' => $classname,
        ]);
    }
}
