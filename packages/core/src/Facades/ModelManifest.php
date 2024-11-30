<?php

namespace Payflow\Facades;

use Illuminate\Support\Facades\Facade;
use Payflow\Base\BaseModel;
use Payflow\Base\ModelManifestInterface;

/**
 * Class ModelManifest.
 *
 * @method static void register()
 * @method static void addDirectory(string $dir)
 * @method static void add(string $interfaceClass, string $modelClass)
 * @method static void replace(string $interfaceClass, string $modelClass)
 * @method static string|null get(string $interfaceClass)
 * @method static string guessContractClass(string $modelClass)
 * @method static string guessModelClass(string $modelContract)
 * @method static bool isPayflowModel(string|BaseModel $model)
 * @method static string getTable(BaseModel $model)
 * @method static void morphMap()
 * @method static string getMorphMapKey(string $className)
 *
 * @see \Payflow\Base\ModelManifest
 */
class ModelManifest extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return ModelManifestInterface::class;
    }
}
