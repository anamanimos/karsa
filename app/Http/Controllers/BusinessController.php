<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BusinessController extends Controller
{
    /**
     * List businesses belonging to the user.
     */
    public function index(): View
    {
        $user = Auth::user();
        $businesses = $user->allBusinesses();

        return view('businesses.index', compact('businesses'));
    }

    /**
     * Show form to create a new business.
     */
    public function create(): View|RedirectResponse
    {
        $user = Auth::user();
        if (!$user->canCreateBusiness()) {
            return redirect()
                ->route('businesses.index')
                ->with('error', "Batas kuota unit usaha Anda telah tercapai ({$user->ownedBusinesses()->count()} dari {$user->maxBusinesses()} toko). Silakan hubungi Administrator atau upgrade paket untuk membuka toko baru.");
        }

        return view('businesses.create');
    }

    /**
     * Store newly created business and initialize standard defaults.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->canCreateBusiness()) {
            return redirect()
                ->route('businesses.index')
                ->with('error', "Batas kuota unit usaha Anda telah tercapai ({$user->ownedBusinesses()->count()} dari {$user->maxBusinesses()} toko). Silakan hubungi Administrator atau upgrade paket untuk membuka toko baru.");
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'string', 'in:general,retail,fnb,service,agriculture,wholesale'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'currency_symbol' => ['nullable', 'string', 'max:10'],
            'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'min_margin' => ['nullable', 'numeric', 'min:0'],
        ]);

        $business = DB::transaction(function () use ($user, $validated) {
            $business = Business::create([
                'owner_id' => $user->id,
                'name' => $validated['name'],
                'business_type' => $validated['business_type'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'] ?? null,
                'currency_symbol' => $validated['currency_symbol'] ?? 'Rp',
                'tax_percentage' => $validated['tax_percentage'] ?? 0,
                'min_margin' => $validated['min_margin'] ?? 1000,
                'is_active' => true,
            ]);

            // Add owner to pivot
            $business->users()->attach($user->id, ['role' => 'owner']);

            // Initialize default units & Chart of Accounts
            $business->initializeDefaults();

            // Set as active business for user
            $user->update(['active_business_id' => $business->id]);

            return $business;
        });

        return redirect()
            ->route('dashboard')
            ->with('success', "Usaha \"{$business->name}\" berhasil dibuat dan diaktifkan!");
    }

    /**
     * Switch active business.
     */
    public function switch(Business $business): RedirectResponse
    {
        $user = Auth::user();

        if ($user->switchBusiness($business)) {
            return redirect()
                ->route('dashboard')
                ->with('success', "Berhasil beralih ke usaha: {$business->name}");
        }

        return redirect()
            ->back()
            ->with('error', 'Anda tidak memiliki akses ke usaha tersebut.');
    }

    /**
     * Edit business profile & settings.
     */
    public function edit(Business $business): View
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $business->owner_id !== $user->id && !$business->users()->where('users.id', $user->id)->wherePivot('role', 'owner')->exists()) {
            abort(403, 'Akses ditolak.');
        }

        return view('businesses.edit', compact('business'));
    }

    /**
     * Update business details.
     */
    public function update(Request $request, Business $business): RedirectResponse
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $business->owner_id !== $user->id && !$business->users()->where('users.id', $user->id)->wherePivot('role', 'owner')->exists()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'string', 'in:general,retail,fnb,service,agriculture,wholesale'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'currency_symbol' => ['nullable', 'string', 'max:10'],
            'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'min_margin' => ['nullable', 'numeric', 'min:0'],
            'require_shift_for_pos' => ['nullable', 'in:0,1'],
            'receipt_footer' => ['nullable', 'string', 'max:500'],
            'telegram_bot_token' => ['nullable', 'string', 'max:255'],
            'telegram_chat_id' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['require_shift_for_pos'] = $request->has('require_shift_for_pos') && $request->require_shift_for_pos == '1';

        $business->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Profil dan pengaturan usaha berhasil diperbarui.');
    }
}
