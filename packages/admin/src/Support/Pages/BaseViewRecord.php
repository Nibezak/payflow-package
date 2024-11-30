<?php

namespace Payflow\Admin\Support\Pages;

use Filament\Resources\Pages\ViewRecord;

abstract class BaseViewRecord extends ViewRecord
{
    use Concerns\ExtendsFooterWidgets;
    use Concerns\ExtendsHeaderActions;
    use Concerns\ExtendsHeaderWidgets;
    use Concerns\ExtendsHeadings;
    use Concerns\ExtendsInfolist;
    use \Payflow\Admin\Support\Concerns\CallsHooks;
    use \Payflow\Admin\Support\Concerns\CallsHooks;
}
