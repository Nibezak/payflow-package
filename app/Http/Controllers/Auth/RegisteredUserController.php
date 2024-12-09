<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;  // Import the Tenant model
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create the tenant for the user
        $tenant = Tenant::create([
            'name' => $request->name . "'s Tenant",  // Customize tenant name
            'domain' => strtolower($request->name) . '.payflow.dev',  // Example domain (customize as needed)
        ]);

        // Create the user and associate with the created tenant
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $tenant->id,  // Associate the tenant_id with the user
        ]);

        // Fire the Registered event (optional, as you may already have it)
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        // Redirect to the login page or desired route
        return redirect(route('login', absolute: false));
    }
}
