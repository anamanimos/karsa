<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPlanController extends Controller
{
    public function index(): View
    {
        $plans = Plan::withCount('users')->orderBy('price')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'max_businesses' => ['required', 'integer', 'min:1'],
            'max_employees_per_business' => ['required', 'integer', 'min:1'],
            'max_products_per_business' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $plan->update($validated);

        return redirect()
            ->route('admin.plans.index')
            ->with('success', "Paket \"{$plan->name}\" berhasil diperbarui.");
    }
}
