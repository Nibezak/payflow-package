<?php

namespace Payflow\Admin\Support\Concerns;

use Payflow\Admin\Support\Facades\PayflowPanel;

trait CallsHooks
{
    protected function callPayflowHook(...$args)
    {
        return PayflowPanel::callHook(static::class, $this, ...$args);
    }

    protected static function callStaticPayflowHook(...$args)
    {
        return PayflowPanel::callHook(static::class, null, ...$args);
    }
}
