<?php

namespace Payflow\Admin\Support\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Dashboard as Page;

class BaseDashboard extends Page
{
    protected static ?string $permission = null;

    public static function registerNavigationItems(): void
    {
        if (! static::hasPermission()) {
            return;
        }

        parent::registerNavigationItems();
    }

    public function mount(): void
    {
        abort_unless(static::hasPermission(), 403);
    }

    protected static function hasPermission(): bool
    {
        if (! static::$permission) {
            return true;
        }

        $user = Filament::auth()->user();

        return $user->can(static::$permission);
    }
}
