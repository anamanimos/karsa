<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): View
    {
        $query = User::query()
            ->withCount(['sales', 'purchases']);

        // Search by name, email, or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role') && in_array($request->role, ['admin', 'kasir'])) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,kasir'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Display the specified user details and activity.
     */
    public function show(User $user): View
    {
        $user->loadCount(['sales', 'purchases']);

        $recentSales = $user->sales()
            ->with('customer')
            ->latest('sale_date')
            ->latest('id')
            ->take(10)
            ->get();

        $recentPurchases = $user->purchases()
            ->with('supplier')
            ->latest('purchase_date')
            ->latest('id')
            ->take(10)
            ->get();

        $totalSalesAmount = $user->sales()->sum('total_amount');
        $totalPurchasesAmount = $user->purchases()->sum('total_amount');

        return view('users.show', compact(
            'user',
            'recentSales',
            'recentPurchases',
            'totalSalesAmount',
            'totalPurchasesAmount'
        ));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,kasir'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Self-protection rules for logged in user
        if ($user->id === Auth::id()) {
            // Cannot deactivate self
            if (!$request->boolean('is_active')) {
                return back()
                    ->withInput()
                    ->with('error', 'Anda tidak dapat menonaktifkan akun yang sedang digunakan.');
            }

            // Cannot demote self from admin
            if ($user->role === 'admin' && $validated['role'] !== 'admin') {
                return back()
                    ->withInput()
                    ->with('error', 'Anda tidak dapat mengubah peran akun Anda sendiri menjadi Kasir.');
            }
        }

        $dataToUpdate = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active'),
        ];

        if (!empty($validated['password'])) {
            $dataToUpdate['password'] = Hash::make($validated['password']);
        }

        $user->update($dataToUpdate);

        return redirect()
            ->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Cannot delete self
        if ($user->id === Auth::id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        // Check if user has associated transactions
        if ($user->sales()->exists() || $user->purchases()->exists()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Pengguna tidak dapat dihapus karena sudah memiliki riwayat transaksi kasir. Silakan nonaktifkan status akun sebagai alternatif.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
