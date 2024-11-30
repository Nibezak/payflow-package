<?php

namespace Payflow\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Payflow\Admin\PayflowPanelManager register()
 * @method static \Payflow\Admin\PayflowPanelManager panel(\Closure $closure)
 * @method static \Filament\Panel getPanel()
 * @method static \Payflow\Admin\PayflowPanelManager extensions(array $extensions)
 * @method static array<class-string<\Filament\Resources\Resource>> getResources()
 * @method static array<class-string<\Filament\Pages\Page>> getPages()
 * @method static array<class-string<\Filament\Widgets\Widget>> getWidgets()
 * @method static \Payflow\Admin\PayflowPanelManager useRoleAsAdmin(string|array $roleHandle)
 * @method static mixed callHook(string $class, object|null $caller, string $hookName, ...$args)
 *
 * @see \Payflow\Admin\PayflowPanelManager
 */
class PayflowPanel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'payflow-panel';
    }
}
