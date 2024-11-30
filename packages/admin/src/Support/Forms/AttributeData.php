<?php

namespace Payflow\Admin\Support\Forms;

use Filament\Forms\Components\Component;
use Illuminate\Support\Collection;
use Payflow\Admin\Support\FieldTypes\Dropdown;
use Payflow\Admin\Support\FieldTypes\File;
use Payflow\Admin\Support\FieldTypes\ListField;
use Payflow\Admin\Support\FieldTypes\Number;
use Payflow\Admin\Support\FieldTypes\TextField;
use Payflow\Admin\Support\FieldTypes\Toggle;
use Payflow\Admin\Support\FieldTypes\TranslatedText;
use Payflow\Admin\Support\FieldTypes\Vimeo;
use Payflow\Admin\Support\FieldTypes\YouTube;
use Payflow\FieldTypes\Dropdown as DrodownFieldType;
use Payflow\FieldTypes\File as FileFieldType;
use Payflow\FieldTypes\ListField as ListFieldFieldType;
use Payflow\FieldTypes\Number as NumberFieldType;
use Payflow\FieldTypes\Text as TextFieldType;
use Payflow\FieldTypes\Toggle as ToggleFieldType;
use Payflow\FieldTypes\TranslatedText as TranslatedTextFieldType;
use Payflow\FieldTypes\Vimeo as VimeoFieldType;
use Payflow\FieldTypes\YouTube as YouTubeFieldType;
use Payflow\Models\Attribute;

class AttributeData
{
    protected array $fieldTypes = [
        DrodownFieldType::class => Dropdown::class,
        ListFieldFieldType::class => ListField::class,
        TextFieldType::class => TextField::class,
        TranslatedTextFieldType::class => TranslatedText::class,
        ToggleFieldType::class => Toggle::class,
        YouTubeFieldType::class => YouTube::class,
        VimeoFieldType::class => Vimeo::class,
        NumberFieldType::class => Number::class,
        FileFieldType::class => File::class,
    ];

    public function getFilamentComponent(Attribute $attribute): Component
    {
        $fieldType = $this->fieldTypes[
        $attribute->type
        ] ?? TextField::class;

        /** @var Component $component */
        $component = $fieldType::getFilamentComponent($attribute);

        return $component
            ->label(
                $attribute->translate('name')
            )
            ->formatStateUsing(function ($state) use ($attribute) {
                if (
                    ! $state ||
                    (get_class($state) != $attribute->type)
                ) {
                    return new $attribute->type;
                }

                return $state;
            })
            ->mutateDehydratedStateUsing(function ($state) use ($attribute) {
                if ($attribute->type == FileFieldType::class) {
                    $instance = new $attribute->type;
                    $instance->setValue($state);

                    return $instance;
                }

                if (
                    ! $state ||
                    (get_class($state) != $attribute->type)
                ) {
                    return new $attribute->type;
                }

                return $state;
            })
            ->required($attribute->required)
            ->default($attribute->default_value);
    }

    public function registerFieldType(string $coreFieldType, string $panelFieldType): static
    {
        $this->fieldTypes[$coreFieldType] = $panelFieldType;

        return $this;
    }

    public function getFieldTypes(): Collection
    {
        return collect($this->fieldTypes)->keys();
    }

    public function getConfigurationFields(?string $type = null): array
    {
        $fieldType = $this->fieldTypes[$type] ?? null;

        return $fieldType ? $fieldType::getConfigurationFields() : [];
    }

    public function synthesizeLivewireProperties(): void
    {
        foreach ($this->fieldTypes as $fieldType) {
            $fieldType::synthesize();
        }
    }
}
