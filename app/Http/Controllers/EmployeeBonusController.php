<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeBonus;
use Illuminate\Http\Request;

class EmployeeBonusController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeBonus::with(['employee', 'creator']);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $bonuses = $query->latest('date')->paginate(15)->withQueryString();
        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total_amount' => (clone $query)->sum('amount'),
            'total_count' => (clone $query)->count(),
        ];

        return view('employees.bonuses', compact('bonuses', 'employees', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'type' => 'required|in:bonus,commission,thr,incentive,other',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        $validated['period'] = date('Y-m', strtotime($validated['date']));
        $validated['created_by'] = auth()->id();

        EmployeeBonus::create($validated);

        return redirect()->back()->with('success', 'Bonus / Komisi berhasil ditambahkan.');
    }

    public function destroy(EmployeeBonus $employeeBonus)
    {
        $employeeBonus->delete();
        return redirect()->back()->with('success', 'Data Bonus berhasil dihapus.');
    }
}
