<?php

namespace Payflow\Admin\Support\Actions\Collections;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Payflow\Admin\Support\Actions\Traits\CreatesChildCollections;
use Payflow\Admin\Support\Forms\Components\TranslatedText;
use Payflow\Models\Attribute;
use Payflow\Models\Collection;

class CreateChildCollection extends CreateAction
{
    use CreatesChildCollections;

    public function setUp(): void
    {
        parent::setUp();

        $this->action(function (?Model $model, array $arguments, array $data): void {
            $parent = Collection::find($arguments['id']);

            $this->createChildCollection($parent, $data['name']);

            $this->success();
        });

        $attribute = Attribute::where('attribute_type', '=', Collection::morphName())
            ->where('handle', '=', 'name')->first();

        $formInput = TextInput::class;

        if ($attribute?->type == \Payflow\FieldTypes\TranslatedText::class) {
            $formInput = TranslatedText::class;
        }

        $this->form([
            $formInput::make('name')->required(),
        ]);

        $this->label(
            __('payflowpanel::actions.collections.create_child.label')
        );

        $this->createAnother(false);

        $this->modalHeading(
            __('payflowpanel::actions.collections.create_child.label')
        );
    }
}
