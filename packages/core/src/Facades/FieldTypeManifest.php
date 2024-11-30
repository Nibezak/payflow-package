<?php

namespace Payflow\Facades;

use Illuminate\Support\Facades\Facade;
use Payflow\Base\FieldTypeManifestInterface;

class FieldTypeManifest extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return FieldTypeManifestInterface::class;
    }
}
