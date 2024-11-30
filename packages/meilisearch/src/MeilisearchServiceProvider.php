<?php

namespace Payflow\Meilisearch;

use Illuminate\Support\ServiceProvider;
use Payflow\Meilisearch\Console\MeilisearchSetup;

class MeilisearchServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MeilisearchSetup::class,
            ]);
        }
    }
}
