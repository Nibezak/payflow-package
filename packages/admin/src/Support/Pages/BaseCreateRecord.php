<?php

namespace Payflow\Admin\Support\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

abstract class BaseCreateRecord extends CreateRecord
{
    use Concerns\ExtendsFooterWidgets;
    use Concerns\ExtendsFormActions;
    use Concerns\ExtendsForms;
    use Concerns\ExtendsHeaderActions;
    use Concerns\ExtendsHeaderWidgets;
    use Concerns\ExtendsHeadings;
    use \Payflow\Admin\Support\Concerns\CallsHooks;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->callPayflowHook('beforeCreate', $data);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data = $this->callPayflowHook('beforeCreation', $data);

        $record = parent::handleRecordCreation($data);

        return $this->callPayflowHook('afterCreation', $record, $data);
    }
}
