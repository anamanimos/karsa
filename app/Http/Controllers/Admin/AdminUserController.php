<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with(['plan', 'ownedBusinesses', 'businesses', 'activeBusiness']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'superadmins' => User::where('role', 'superadmin')->count(),
            'users' => User::where('role', 'user')->count(),
            'cashiers' => User::where('role', 'kasir')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create(): View
    {
        $businesses = Business::where('is_active', true)->orderBy('name')->get();
        $plans = \App\Models\Plan::where('is_active', true)->get();
        return view('admin.users.create', compact('businesses', 'plans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:superadmin,user,kasir'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'custom_max_businesses' => ['nullable', 'integer', 'min:1'],
            'custom_max_employees' => ['nullable', 'integer', 'min:1'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'active_business_id' => ['nullable', 'exists:businesses,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'plan_id' => $validated['plan_id'] ?? \App\Models\Plan::defaultPlan()?->id,
            'custom_max_businesses' => $validated['custom_max_businesses'] ?? null,
            'custom_max_employees' => $validated['custom_max_employees'] ?? null,
            'password' => Hash::make($validated['password']),
            'active_business_id' => $validated['active_business_id'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (!empty($validated['active_business_id'])) {
            $user->businesses()->syncWithoutDetaching([
                $validated['active_business_id'] => ['role' => $validated['role'] === 'kasir' ? 'cashier' : 'staff']
            ]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Pengguna baru \"{$user->name}\" ({$user->role}) berhasil didaftarkan.");
    }

    public function show(User $user): View
    {
        $user->load(['plan', 'ownedBusinesses', 'businesses', 'activeBusiness']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $businesses = Business::where('is_active', true)->orderBy('name')->get();
        $plans = \App\Models\Plan::where('is_active', true)->get();
        return view('admin.users.edit', compact('user', 'businesses', 'plans'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:superadmin,user,kasir'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'custom_max_businesses' => ['nullable', 'integer', 'min:1'],
            'custom_max_employees' => ['nullable', 'integer', 'min:1'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'active_business_id' => ['nullable', 'exists:businesses,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($user->id === Auth::id()) {
            if (!$request->boolean('is_active', true)) {
                return back()->withInput()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
            }
            if ($user->role === 'superadmin' && $validated['role'] !== 'superadmin') {
                return back()->withInput()->with('error', 'Anda tidak dapat mencabut hak Super Admin dari akun Anda sendiri.');
            }
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'plan_id' => $validated['plan_id'] ?? null,
            'custom_max_businesses' => $request->filled('custom_max_businesses') ? (int) $validated['custom_max_businesses'] : null,
            'custom_max_employees' => $request->filled('custom_max_employees') ? (int) $validated['custom_max_employees'] : null,
            'active_business_id' => $validated['active_business_id'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        if (!empty($validated['active_business_id'])) {
            $user->businesses()->syncWithoutDetaching([
                $validated['active_business_id'] => ['role' => $validated['role'] === 'kasir' ? 'cashier' : 'staff']
            ]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Data pengguna \"{$user->name}\" berhasil diperbarui.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Pengguna \"{$name}\" berhasil dihapus.");
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah status akun Anda sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Status akun \"{$user->name}\" berhasil {$statusText}.");
    }
}
