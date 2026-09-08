<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use Illuminate\Http\Request;

class EmployeeAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', date('Y-m'));

        $employees = Employee::where('is_active', true)
            ->with(['attendances' => function ($q) use ($period) {
                $q->where('period', $period);
            }])
            ->orderBy('name')
            ->get();

        return view('employees.attendance', compact('employees', 'period'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'period' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'attendances' => 'required|array',
            'attendances.*.employee_id' => 'required|exists:employees,id',
            'attendances.*.work_days' => 'required|integer|min:0|max:31',
            'attendances.*.present_days' => 'required|integer|min:0|max:31',
            'attendances.*.sick_days' => 'nullable|integer|min:0|max:31',
            'attendances.*.permission_days' => 'nullable|integer|min:0|max:31',
            'attendances.*.absent_days' => 'nullable|integer|min:0|max:31',
            'attendances.*.overtime_hours' => 'nullable|numeric|min:0',
            'attendances.*.notes' => 'nullable|string',
        ]);

        foreach ($validated['attendances'] as $att) {
            EmployeeAttendance::updateOrCreate(
                [
                    'employee_id' => $att['employee_id'],
                    'period' => $validated['period'],
                ],
                [
                    'work_days' => $att['work_days'],
                    'present_days' => $att['present_days'],
                    'sick_days' => $att['sick_days'] ?? 0,
                    'permission_days' => $att['permission_days'] ?? 0,
                    'absent_days' => $att['absent_days'] ?? 0,
                    'overtime_hours' => $att['overtime_hours'] ?? 0,
                    'notes' => $att['notes'] ?? null,
                ]
            );
        }

        return redirect()->route('employee-attendances.index', ['period' => $validated['period']])
            ->with('success', 'Data presensi & kehadiran periode ' . $validated['period'] . ' berhasil disimpan.');
    }
}
