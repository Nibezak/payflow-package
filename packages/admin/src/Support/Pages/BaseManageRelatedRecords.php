<?php

namespace Payflow\Admin\Support\Pages;

use Filament\Resources\Pages\ManageRelatedRecords;

abstract class BaseManageRelatedRecords extends ManageRelatedRecords
{
    use Concerns\ExtendsFooterWidgets;
    use Concerns\ExtendsHeaderActions;
    use Concerns\ExtendsHeaderWidgets;
    use Concerns\ExtendsHeadings;
    use \Payflow\Admin\Support\Concerns\CallsHooks;
}
