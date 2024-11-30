<?php

namespace Payflow\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface Asset
{
    public function file(): MorphOne;
}
