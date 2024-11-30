<?php

namespace Payflow\Tests\Core\Stubs\Models;

class Product extends \Payflow\Models\Product
{
    use SearchableTrait;

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        return false;
    }
}
