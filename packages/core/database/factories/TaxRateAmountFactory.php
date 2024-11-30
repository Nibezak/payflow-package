<?php

namespace Payflow\Database\Factories;

use Payflow\Models\TaxClass;
use Payflow\Models\TaxRate;
use Payflow\Models\TaxRateAmount;

class TaxRateAmountFactory extends BaseFactory
{
    protected $model = TaxRateAmount::class;

    public function definition(): array
    {
        return [
            'tax_rate_id' => TaxRate::factory(),
            'tax_class_id' => TaxClass::factory(),
            'percentage' => 20,
        ];
    }
}
