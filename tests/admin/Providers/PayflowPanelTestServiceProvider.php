<?php

namespace Payflow\Tests\Admin\Providers;

use Illuminate\Support\ServiceProvider;

class PayflowPanelTestServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        \Payflow\Admin\Support\Facades\PayflowPanel::register();
    }
}
