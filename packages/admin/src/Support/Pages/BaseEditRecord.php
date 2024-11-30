<?php

namespace Payflow\Admin\Support\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

abstract class BaseEditRecord extends EditRecord
{
    use Concerns\ExtendsFooterWidgets;
    use Concerns\ExtendsFormActions;
    use Concerns\ExtendsForms;
    use Concerns\ExtendsHeaderActions;
    use Concerns\ExtendsHeaderWidgets;
    use Concerns\ExtendsHeadings;
    use \Payflow\Admin\Support\Concerns\CallsHooks;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->callPayflowHook('beforeFill', $data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->callPayflowHook('beforeSave', $data);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $data = $this->callPayflowHook('beforeUpdate', $data, $record);

        $record = parent::handleRecordUpdate($record, $data);

        return $this->callPayflowHook('afterUpdate', $record, $data);
    }

    public function afterSave()
    {
        sync_with_search(
            $this->getRecord()
        );
    }
}
