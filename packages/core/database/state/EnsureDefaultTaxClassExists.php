<?php

namespace Payflow\Database\State;

use Illuminate\Support\Facades\Schema;
use Payflow\Models\TaxClass;

class EnsureDefaultTaxClassExists
{
    public function prepare()
    {
        //
    }

    public function run()
    {
        if (! $this->canRun() || ! $this->shouldRun()) {
            return;
        }

        // Get the first tax class and make it default
        $taxClass = TaxClass::first();

        if ($taxClass) {
            $taxClass->update([
                'default' => true,
            ]);
        }
    }

    protected function canRun()
    {
        $prefix = config('payflow.database.table_prefix');

        return Schema::hasTable("{$prefix}tax_classes");
    }

    protected function shouldRun()
    {
        return ! TaxClass::whereDefault(true)->count();
    }
}
