<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Payflow\Admin\Support\Facades\PayflowPanel;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registering the PayflowPanel service
        PayflowPanel::register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register route model bindings if needed
        // Example: Customizing model binding for a user
        // Route::model('user', User::class);

        // Define any gates or policies here
        Gate::define('admin', function ($user) {
            return $user->is_admin;
        });

        // If you need to perform any other bootstrapping logic, you can add here.
    }
}
