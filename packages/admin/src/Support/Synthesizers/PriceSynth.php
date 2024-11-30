<?php

namespace Payflow\Admin\Support\Synthesizers;

use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
use Payflow\DataTypes\Price;
use Payflow\Models\Currency;

final class PriceSynth extends Synth
{
    public static $key = 'payflow_price';

    public static function match($target)
    {
        return $target instanceof Price;
    }

    public function dehydrate($target)
    {
        return [[
            'value' => $target->value,
            'currency' => $target->currency->code,
            'unitQty' => $target->unitQty,
        ], []];
    }

    public function hydrate($value)
    {
        $currency = Currency::where('code', $value['currency'])->first();

        return new Price($value['value'], $currency, $value['unitQty']);
    }
}
