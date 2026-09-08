<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CashTransaction;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeBonus;
use App\Models\Payroll;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', date('Y-m'));

        $query = Payroll::with(['employee', 'paidFromAccount', 'cashTransaction'])
            ->where('period', $period);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->orderBy('payroll_number')->paginate(15)->withQueryString();

        $accounts = Account::where('subtype', 'cash_bank')->where('is_active', true)->get();

        $stats = [
            'total_payrolls' => Payroll::where('period', $period)->count(),
            'total_net_salary' => Payroll::where('period', $period)->sum('net_salary'),
            'total_paid' => Payroll::where('period', $period)->where('status', 'paid')->sum('net_salary'),
            'total_pending' => Payroll::where('period', $period)->whereIn('status', ['draft', 'approved'])->sum('net_salary'),
        ];

        return view('payrolls.index', compact('payrolls', 'period', 'accounts', 'stats'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'period' => 'required|string|regex:/^\d{4}-\d{2}$/',
        ]);

        $period = $validated['period'];
        $employees = Employee::where('is_active', true)->get();

        if ($employees->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada data karyawan aktif untuk digenerate.');
        }

        $generatedCount = 0;

        DB::transaction(function () use ($employees, $period, &$generatedCount) {
            $periodClean = str_replace('-', '', $period);

            foreach ($employees as $index => $emp) {
                // Check if payroll already exists and is paid
                $existing = Payroll::where('employee_id', $emp->id)->where('period', $period)->first();
                if ($existing && $existing->status === 'paid') {
                    continue;
                }

                // Get attendance
                $att = EmployeeAttendance::where('employee_id', $emp->id)->where('period', $period)->first();
                $presentDays = $att ? $att->present_days : 26;
                $workDays = $att ? $att->work_days : 26;
                $absentDays = $att ? $att->absent_days : 0;
                $overtimeHours = $att ? (float) $att->overtime_hours : 0;

                // Base allowances
                $baseSalary = (float) $emp->base_salary;
                $fixedAllowance = (float) $emp->fixed_allowance;
                $attendanceAllowance = $presentDays * (float) $emp->daily_allowance;
                $overtimePay = $overtimeHours * (float) $emp->overtime_rate_per_hour;

                // Sum bonuses for this month
                $bonusPay = (float) EmployeeBonus::where('employee_id', $emp->id)
                    ->where('period', $period)
                    ->sum('amount');

                $totalAllowances = $baseSalary + $fixedAllowance + $attendanceAllowance + $overtimePay + $bonusPay;

                // Deductions (absence deduction if any)
                $dailyRate = $workDays > 0 ? ($baseSalary / $workDays) : 0;
                $absenceDeduction = $absentDays * $dailyRate;
                $loanDeduction = $existing ? $existing->loan_deduction : 0;
                $bpjsDeduction = $existing ? $existing->bpjs_deduction : 0;
                $otherDeductions = $existing ? $existing->other_deductions : 0;

                $totalDeductions = $absenceDeduction + $loanDeduction + $bpjsDeduction + $otherDeductions;
                $netSalary = max(0, $totalAllowances - $totalDeductions);

                $payrollNumber = 'PAY-' . $periodClean . '-' . str_pad($emp->id, 4, '0', STR_PAD_LEFT);

                Payroll::updateOrCreate(
                    [
                        'employee_id' => $emp->id,
                        'period' => $period,
                    ],
                    [
                        'payroll_number' => $payrollNumber,
                        'base_salary' => $baseSalary,
                        'fixed_allowance' => $fixedAllowance,
                        'attendance_allowance' => $attendanceAllowance,
                        'overtime_pay' => $overtimePay,
                        'bonus_pay' => $bonusPay,
                        'total_allowances' => $totalAllowances,
                        'absence_deduction' => $absenceDeduction,
                        'loan_deduction' => $loanDeduction,
                        'bpjs_deduction' => $bpjsDeduction,
                        'other_deductions' => $otherDeductions,
                        'total_deductions' => $totalDeductions,
                        'net_salary' => $netSalary,
                        'status' => 'draft',
                        'created_by' => auth()->id(),
                    ]
                );

                $generatedCount++;
            }
        });

        return redirect()->route('payrolls.index', ['period' => $period])
            ->with('success', "Berhasil men-generate $generatedCount slip gaji untuk periode $period.");
    }

    public function edit(Payroll $payroll)
    {
        return view('payrolls.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return redirect()->back()->with('error', 'Slip gaji yang sudah dibayar tidak dapat diedit.');
        }

        $validated = $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'fixed_allowance' => 'nullable|numeric|min:0',
            'attendance_allowance' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'bonus_pay' => 'nullable|numeric|min:0',
            'absence_deduction' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'bpjs_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $base = (float) $validated['base_salary'];
        $fixed = (float) ($validated['fixed_allowance'] ?? 0);
        $att = (float) ($validated['attendance_allowance'] ?? 0);
        $overtime = (float) ($validated['overtime_pay'] ?? 0);
        $bonus = (float) ($validated['bonus_pay'] ?? 0);
        $totalAllowances = $base + $fixed + $att + $overtime + $bonus;

        $abs = (float) ($validated['absence_deduction'] ?? 0);
        $loan = (float) ($validated['loan_deduction'] ?? 0);
        $bpjs = (float) ($validated['bpjs_deduction'] ?? 0);
        $other = (float) ($validated['other_deductions'] ?? 0);
        $totalDeductions = $abs + $loan + $bpjs + $other;

        $netSalary = max(0, $totalAllowances - $totalDeductions);

        $validated['total_allowances'] = $totalAllowances;
        $validated['total_deductions'] = $totalDeductions;
        $validated['net_salary'] = $netSalary;

        $payroll->update($validated);

        return redirect()->route('payrolls.index', ['period' => $payroll->period])
            ->with('success', 'Rincian slip gaji berhasil diperbarui.');
    }

    public function approve(Payroll $payroll)
    {
        if ($payroll->status === 'draft') {
            $payroll->update(['status' => 'approved']);
            return redirect()->back()->with('success', 'Status gaji disetujui (Approved).');
        }

        return redirect()->back()->with('error', 'Status tidak valid untuk disetujui.');
    }

    public function pay(Request $request, Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return redirect()->back()->with('error', 'Gaji sudah berstatus lunas (Paid).');
        }

        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($payroll, $validated) {
            $account = Account::findOrFail($validated['account_id']);

            // Create cash/bank transaction (Expense out)
            $cashTransaction = CashTransaction::create([
                'type' => 'out',
                'amount' => $payroll->net_salary,
                'account_id' => $account->id,
                'category' => 'Beban Gaji & Bonus Karyawan',
                'description' => "Pembayaran gaji {$payroll->employee->name} ({$payroll->employee->code}) Periode {$payroll->period} [{$payroll->payroll_number}]",
                'transaction_date' => $validated['payment_date'],
                'reference_type' => Payroll::class,
                'reference_id' => $payroll->id,
                'created_by' => auth()->id(),
            ]);

            $payroll->update([
                'status' => 'paid',
                'payment_date' => $validated['payment_date'],
                'paid_from_account_id' => $account->id,
                'cash_transaction_id' => $cashTransaction->id,
                'notes' => $validated['notes'] ?? $payroll->notes,
            ]);
        });

        return redirect()->back()->with('success', "Gaji {$payroll->employee->name} berhasil dibayarkan dan otomatis dicatat ke Buku Kas & Laporan Keuangan.");
    }

    public function slip(Payroll $payroll)
    {
        $payroll->load(['employee', 'paidFromAccount', 'creator']);
        $companyName = Setting::get('company_name', 'KarsaERP');
        $companyAddress = Setting::get('company_address', '');
        $companyPhone = Setting::get('company_phone', '');

        return view('payrolls.slip', compact('payroll', 'companyName', 'companyAddress', 'companyPhone'));
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return redirect()->back()->with('error', 'Gaji yang sudah dibayarkan tidak dapat dihapus.');
        }

        $payroll->delete();
        return redirect()->back()->with('success', 'Slip gaji berhasil dihapus.');
    }
}
