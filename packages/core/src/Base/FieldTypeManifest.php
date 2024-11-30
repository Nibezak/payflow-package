<?php

namespace Payflow\Base;

use Payflow\Exceptions\FieldTypes\FieldTypeMissingException;
use Payflow\Exceptions\FieldTypes\InvalidFieldTypeException;
use Payflow\FieldTypes\Dropdown;
use Payflow\FieldTypes\File;
use Payflow\FieldTypes\ListField;
use Payflow\FieldTypes\Number;
use Payflow\FieldTypes\Text;
use Payflow\FieldTypes\Toggle;
use Payflow\FieldTypes\TranslatedText;
use Payflow\FieldTypes\YouTube;

class FieldTypeManifest
{
    /**
     * The FieldTypes available in Payflow.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $fieldTypes;

    public function __construct()
    {
        $this->fieldTypes = collect([
            Dropdown::class,
            ListField::class,
            Number::class,
            Text::class,
            Toggle::class,
            TranslatedText::class,
            YouTube::class,
            File::class,
        ]);
    }

    /**
     * Add a FieldType into Payflow.
     *
     * @param  string  $classname
     * @return void
     */
    public function add($classname)
    {
        if (! class_exists($classname)) {
            throw new FieldTypeMissingException($classname);
        }

        if (! (app()->make($classname) instanceof FieldType)) {
            throw new InvalidFieldTypeException($classname);
        }

        $this->fieldTypes->push($classname);
    }

    /**
     * Return the fieldtypes.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTypes()
    {
        return $this->fieldTypes->map(fn ($type) => app()->make($type));
    }
}
