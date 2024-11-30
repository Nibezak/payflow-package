<?php

namespace Payflow\Base;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Payflow\Models\Attribute;
use Payflow\Models\Brand;
use Payflow\Models\Collection as ModelsCollection;
use Payflow\Models\Customer;
use Payflow\Models\CustomerGroup;
use Payflow\Models\Product;
use Payflow\Models\ProductVariant;

class AttributeManifest
{
    /**
     * A collection of available attribute types.
     */
    protected Collection $types;

    protected Collection $searchableAttributes;

    protected $baseTypes = [
        Product::class,
        ProductVariant::class,
        ModelsCollection::class,
        Customer::class,
        Brand::class,
        CustomerGroup::class,
        // Order::class,
    ];

    /**
     * Initialise the class.
     */
    public function __construct()
    {
        $this->types = collect();
        $this->searchableAttributes = collect();

        foreach ($this->baseTypes as $type) {
            $this->addType($type);
        }
    }

    public function addType($type, $key = null)
    {
        $this->types->prepend(
            $type,
            $key ?: Str::lower(
                class_basename($type)
            )
        );
    }

    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function getType($key)
    {
        return $this->types[$key] ?? null;
    }

    public function getSearchableAttributes(string $attributeType)
    {
        $attributes = $this->searchableAttributes->get($attributeType, null);

        if ($attributes) {
            return $attributes;
        }

        $attributes = Attribute::whereAttributeType($attributeType)
            ->whereSearchable(true)
            ->get();

        $this->searchableAttributes->put(
            $attributeType,
            $attributes
        );

        return $attributes;
    }
}
