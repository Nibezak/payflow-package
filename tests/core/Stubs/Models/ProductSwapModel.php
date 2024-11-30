<?php

namespace Payflow\Tests\Core\Stubs\Models;

class ProductSwapModel extends \Payflow\Models\Product
{
    public function shouldBeSearchable()
    {
        return false;
    }
}
