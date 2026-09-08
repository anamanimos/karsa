<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'attendances', 'bonuses']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $employees = $query->orderBy('name')->paginate(15)->withQueryString();

        $business = \App\Helpers\TenantHelper::currentBusiness();
        $owner = $business?->owner ?? auth()->user();
        $maxEmployees = $owner ? $owner->maxEmployeesPerBusiness() : 3;
        $canAddEmployee = $owner ? $owner->canCreateEmployee($business) : true;

        $stats = [
            'total' => Employee::count(),
            'active' => Employee::where('is_active', true)->count(),
            'total_base_payroll' => Employee::where('is_active', true)->sum('base_salary'),
            'max_employees' => $maxEmployees,
            'can_add' => $canAddEmployee,
        ];

        return view('employees.index', compact('employees', 'stats'));
    }

    public function create()
    {
        $business = \App\Helpers\TenantHelper::currentBusiness();
        $owner = $business?->owner ?? auth()->user();
        if ($owner && !$owner->canCreateEmployee($business)) {
            $max = $owner->maxEmployeesPerBusiness();
            return redirect()
                ->route('employees.index')
                ->with('error', "Batas kuota karyawan untuk unit usaha ini telah tercapai (maksimal {$max} karyawan aktif). Silakan hubungi Administrator atau upgrade paket untuk menambah karyawan.");
        }

        $users = User::whereDoesntHave('employee')->orWhere('is_active', true)->get();
        return view('employees.create', compact('users'));
    }

    public function store(Request $request)
    {
        $business = \App\Helpers\TenantHelper::currentBusiness();
        $owner = $business?->owner ?? auth()->user();
        if ($owner && !$owner->canCreateEmployee($business)) {
            $max = $owner->maxEmployeesPerBusiness();
            return redirect()
                ->route('employees.index')
                ->with('error', "Batas kuota karyawan untuk unit usaha ini telah tercapai (maksimal {$max} karyawan aktif). Silakan hubungi Administrator atau upgrade paket untuk menambah karyawan.");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:employees,code',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'position' => 'required|string|max:100',
            'employment_status' => 'required|in:permanent,contract,probation,daily',
            'join_date' => 'nullable|date',
            'base_salary' => 'required|numeric|min:0',
            'fixed_allowance' => 'nullable|numeric|min:0',
            'daily_allowance' => 'nullable|numeric|min:0',
            'overtime_rate_per_hour' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_account_holder' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['fixed_allowance'] = $validated['fixed_allowance'] ?? 0;
        $validated['daily_allowance'] = $validated['daily_allowance'] ?? 0;
        $validated['overtime_rate_per_hour'] = $validated['overtime_rate_per_hour'] ?? 0;
        $validated['commission_rate'] = $validated['commission_rate'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Data Karyawan berhasil ditambahkan.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'attendances' => function($q) {
            $q->orderBy('period', 'desc')->take(12);
        }, 'bonuses' => function($q) {
            $q->orderBy('date', 'desc')->take(20);
        }, 'payrolls' => function($q) {
            $q->orderBy('period', 'desc')->take(12);
        }]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $users = User::all();
        return view('employees.edit', compact('employee', 'users'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:employees,code,' . $employee->id,
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'position' => 'required|string|max:100',
            'employment_status' => 'required|in:permanent,contract,probation,daily',
            'join_date' => 'nullable|date',
            'base_salary' => 'required|numeric|min:0',
            'fixed_allowance' => 'nullable|numeric|min:0',
            'daily_allowance' => 'nullable|numeric|min:0',
            'overtime_rate_per_hour' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_account_holder' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $validated['fixed_allowance'] = $validated['fixed_allowance'] ?? 0;
        $validated['daily_allowance'] = $validated['daily_allowance'] ?? 0;
        $validated['overtime_rate_per_hour'] = $validated['overtime_rate_per_hour'] ?? 0;
        $validated['commission_rate'] = $validated['commission_rate'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Data Karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Data Karyawan berhasil dihapus.');
    }
}
