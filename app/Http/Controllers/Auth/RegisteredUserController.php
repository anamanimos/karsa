<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'business_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'string', 'in:general,retail,fnb,service,agriculture,wholesale'],
        ]);

        $defaultPlan = \App\Models\Plan::defaultPlan();

        $user = DB::transaction(function () use ($validated, $defaultPlan) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'role' => 'user',
                'plan_id' => $defaultPlan?->id,
                'password' => Hash::make($validated['password']),
                'is_active' => true,
            ]);

            $business = Business::create([
                'owner_id' => $user->id,
                'name' => $validated['business_name'],
                'business_type' => $validated['business_type'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'],
                'currency_symbol' => 'Rp',
                'is_active' => true,
            ]);

            $business->users()->attach($user->id, ['role' => 'owner']);
            $business->initializeDefaults();

            $user->update(['active_business_id' => $business->id]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()
            ->route('dashboard')
            ->with('success', "Selamat datang di KarsaERP! Usaha \"{$validated['business_name']}\" siap digunakan.");
    }
}
