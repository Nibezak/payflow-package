<?php

namespace Payflow\Database\Factories;

use Payflow\Models\CustomerGroup;
use Payflow\Models\TaxZone;
use Payflow\Models\TaxZoneCustomerGroup;

class TaxZoneCustomerGroupFactory extends BaseFactory
{
    protected $model = TaxZoneCustomerGroup::class;

    public function definition(): array
    {
        return [
            'customer_group_id' => CustomerGroup::factory(),
            'tax_zone_id' => TaxZone::factory(),
        ];
    }
}
