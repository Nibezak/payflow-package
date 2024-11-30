<?php

namespace Payflow\Opayo\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Payflow\Opayo\Models\OpayoToken;

trait HasOpayoTokens
{
    public function opayoTokens(): HasMany
    {
        return $this->hasMany(OpayoToken::class);
    }
}
