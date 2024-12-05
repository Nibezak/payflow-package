<?php

namespace Payflow\Base\Casts;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;
use Payflow\Base\FieldType;
use Payflow\Exceptions\FieldTypeException;

class AsAttributeData implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @return object|string
     */
    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                if (! isset($attributes[$key])) {
                    return null;
                }

                $data = json_decode($attributes[$key], true);

                $returnData = new Collection;

                foreach ($data as $key => $item) {
                    if (! class_exists($item['field_type'])) {
                        continue;
                    }
                    if (! in_array(FieldType::class, class_implements($item['field_type']))) {
                        throw new FieldTypeException('This field type is not supported.');
                    }
                    $returnData->put($key, new $item['field_type']($item['value']));
                }

                return $returnData;
            }

            public function set($model, $key, $value, $attributes)
            {
                $data = [];

                foreach ($value ?? [] as $handle => $item) {
                    $data[$handle] = [
                        'field_type' => is_object($item) ? get_class($item) : 'string', // Handle non-object values
                        'value' => is_object($item) ? $item->getValue() : $item, // Use the raw value for non-objects
                    ];
                }

                return [$key => json_encode($data)];
            }
        };
    }
}
