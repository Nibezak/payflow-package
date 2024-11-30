<?php

namespace Payflow\Tests\Core\Stubs\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class ProductOption extends \Payflow\Models\ProductOption
{
    use SizesTrait;

    /**
     * Get the tags
     */
    public function sizes(): HasMany
    {
        return $this->values()->where('id', 1);
    }

    public static function getSizesStatic(): Collection
    {
        return static::find(1)->values;
    }
}
