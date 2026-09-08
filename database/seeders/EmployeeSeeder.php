<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeBonus;
use App\Models\Payroll;
use App\Models\CashTransaction;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@postani.com')->first();
        $kasirUser = User::where('role', 'kasir')->first();

        $businessId = $adminUser?->active_business_id ?? 1;
        session(['active_business_id' => $businessId]);

        $bcaAccount = Account::where('code', '1003')->first() 
            ?? Account::where('type', 'asset')->where('subtype', 'cash_bank')->first();
        $kasUtamaAccount = Account::where('code', '1002')->first() 
            ?? Account::where('type', 'asset')->where('subtype', 'cash_bank')->first();

        $employeesData = [
            [
                'user_id' => $adminUser?->id,
                'code' => 'EMP-001',
                'name' => 'Budi Santoso',
                'phone' => '081234567891',
                'email' => 'budi@saaserp.local',
                'position' => 'Store Manager & Supervisor',
                'employment_status' => 'permanent',
                'join_date' => '2025-01-15',
                'base_salary' => 4500000,
                'fixed_allowance' => 1000000,
                'daily_allowance' => 30000,
                'overtime_rate_per_hour' => 35000,
                'commission_rate' => 0.00,
                'bank_name' => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_holder' => 'Budi Santoso',
                'notes' => 'Penanggung jawab operasional & laporan toko',
                'is_active' => true,
            ],
            [
                'user_id' => $kasirUser?->id,
                'code' => 'EMP-002',
                'name' => 'Siti Rahmawati',
                'phone' => '081298765432',
                'email' => 'siti@saaserp.local',
                'position' => 'Kasir Senior (Front Office)',
                'employment_status' => 'permanent',
                'join_date' => '2025-03-01',
                'base_salary' => 3200000,
                'fixed_allowance' => 400000,
                'daily_allowance' => 25000,
                'overtime_rate_per_hour' => 25000,
                'commission_rate' => 1.00,
                'bank_name' => 'BRI',
                'bank_account_number' => '9876543210',
                'bank_account_holder' => 'Siti Rahmawati',
                'notes' => 'Kasir utama shift pagi',
                'is_active' => true,
            ],
            [
                'user_id' => null,
                'code' => 'EMP-003',
                'name' => 'Ahmad Fauzi',
                'phone' => '085712349876',
                'email' => 'ahmad@saaserp.local',
                'position' => 'Staff Logistik & Gudang',
                'employment_status' => 'contract',
                'join_date' => '2025-06-10',
                'base_salary' => 3000000,
                'fixed_allowance' => 300000,
                'daily_allowance' => 25000,
                'overtime_rate_per_hour' => 25000,
                'commission_rate' => 0.00,
                'bank_name' => 'Mandiri',
                'bank_account_number' => '5544332211',
                'bank_account_holder' => 'Ahmad Fauzi',
                'notes' => 'Penerimaan barang dan penataan rak stok',
                'is_active' => true,
            ],
            [
                'user_id' => null,
                'code' => 'EMP-010',
                'name' => 'Rina Melati',
                'phone' => '081377889900',
                'email' => 'rina@saaserp.local',
                'position' => 'Kasir & Admin Keuangan',
                'employment_status' => 'contract',
                'join_date' => '2025-08-01',
                'base_salary' => 2900000,
                'fixed_allowance' => 300000,
                'daily_allowance' => 25000,
                'overtime_rate_per_hour' => 25000,
                'commission_rate' => 0.00,
                'bank_name' => 'BNI',
                'bank_account_number' => '7788990011',
                'bank_account_holder' => 'Rina Melati',
                'notes' => 'Rekap harian buku kas & kasir shift siang',
                'is_active' => true,
            ],
        ];

        $employees = [];
        foreach ($employeesData as $empData) {
            $empData['business_id'] = $businessId;
            $employees[] = Employee::updateOrCreate(['code' => $empData['code']], $empData);
        }

        $prevPeriod = Carbon::now()->subMonth()->format('Y-m');
        $currentPeriod = Carbon::now()->format('Y-m');

        // ================= 1. PREVIOUS MONTH PAYROLL (STATUS: PAID) =================
        foreach ($employees as $idx => $employee) {
            $workDays = 26;
            $presentDays = 25;
            $overtimeHours = ($idx === 0 ? 6 : ($idx === 1 ? 8 : 4));
            $attendanceAllowance = $presentDays * $employee->daily_allowance;
            $overtimePay = $overtimeHours * $employee->overtime_rate_per_hour;
            $bonusPay = ($idx === 0 ? 500000 : ($idx === 1 ? 350000 : 200000));
            $totalAllowances = $employee->base_salary + $employee->fixed_allowance + $attendanceAllowance + $overtimePay + $bonusPay;

            $bpjs = 100000;
            $loan = ($idx === 2 ? 150000 : 0);
            $totalDeductions = $bpjs + $loan;
            $netSalary = $totalAllowances - $totalDeductions;

            // Attendance
            EmployeeAttendance::updateOrCreate(
                ['employee_id' => $employee->id, 'period' => $prevPeriod],
                [
                    'business_id' => $businessId,
                    'work_days' => $workDays,
                    'present_days' => $presentDays,
                    'sick_days' => 1,
                    'permission_days' => 0,
                    'absent_days' => 0,
                    'overtime_hours' => $overtimeHours,
                    'notes' => 'Presensi bulan lalu lengkap & terverifikasi',
                ]
            );

            // Bonus
            EmployeeBonus::updateOrCreate(
                ['employee_id' => $employee->id, 'period' => $prevPeriod, 'title' => 'Bonus Prestasi & Target'],
                [
                    'business_id' => $businessId,
                    'date' => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
                    'type' => 'bonus',
                    'amount' => $bonusPay,
                    'description' => "Bonus pencapaian target kerja periode $prevPeriod",
                    'created_by' => $adminUser?->id,
                ]
            );

            // Payroll Paid
            $payrollNumber = 'PAY-' . str_replace('-', '', $prevPeriod) . '-' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT);
            $paymentDate = Carbon::now()->subMonth()->endOfMonth()->toDateString();

            $payroll = Payroll::updateOrCreate(
                ['employee_id' => $employee->id, 'period' => $prevPeriod],
                [
                    'business_id' => $businessId,
                    'payroll_number' => $payrollNumber,
                    'payment_date' => $paymentDate,
                    'base_salary' => $employee->base_salary,
                    'fixed_allowance' => $employee->fixed_allowance,
                    'attendance_allowance' => $attendanceAllowance,
                    'overtime_pay' => $overtimePay,
                    'bonus_pay' => $bonusPay,
                    'total_allowances' => $totalAllowances,
                    'absence_deduction' => 0,
                    'loan_deduction' => $loan,
                    'bpjs_deduction' => $bpjs,
                    'other_deductions' => 0,
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $netSalary,
                    'status' => 'paid',
                    'paid_from_account_id' => $bcaAccount?->id,
                    'notes' => "Gaji periode $prevPeriod telah ditransfer via Bank {$employee->bank_name}",
                    'created_by' => $adminUser?->id,
                ]
            );

            // Create CashTransaction for Paid Payroll
            $cashTx = CashTransaction::updateOrCreate(
                [
                    'reference_type' => Payroll::class,
                    'reference_id' => $payroll->id,
                ],
                [
                    'business_id' => $businessId,
                    'type' => 'out',
                    'amount' => $netSalary,
                    'account_id' => $bcaAccount?->id,
                    'category' => 'Beban Gaji & Bonus Karyawan',
                    'description' => "Pembayaran gaji {$employee->name} ({$employee->code}) Periode {$prevPeriod} [{$payrollNumber}]",
                    'transaction_date' => $paymentDate,
                    'created_by' => $adminUser?->id,
                ]
            );

            $payroll->update(['cash_transaction_id' => $cashTx->id]);
        }

        // ================= 2. CURRENT MONTH PAYROLL & ATTENDANCE =================
        foreach ($employees as $idx => $employee) {
            $workDays = 26;
            $presentDays = 24;
            $overtimeHours = ($idx === 1 ? 5 : 2);
            $attendanceAllowance = $presentDays * $employee->daily_allowance;
            $overtimePay = $overtimeHours * $employee->overtime_rate_per_hour;
            $bonusPay = ($idx === 1 ? 250000 : 150000);
            $totalAllowances = $employee->base_salary + $employee->fixed_allowance + $attendanceAllowance + $overtimePay + $bonusPay;

            $bpjs = 100000;
            $totalDeductions = $bpjs;
            $netSalary = $totalAllowances - $totalDeductions;

            EmployeeAttendance::updateOrCreate(
                ['employee_id' => $employee->id, 'period' => $currentPeriod],
                [
                    'business_id' => $businessId,
                    'work_days' => $workDays,
                    'present_days' => $presentDays,
                    'sick_days' => 1,
                    'permission_days' => 1,
                    'absent_days' => 0,
                    'overtime_hours' => $overtimeHours,
                    'notes' => 'Presensi bulan berjalan',
                ]
            );

            EmployeeBonus::updateOrCreate(
                ['employee_id' => $employee->id, 'period' => $currentPeriod, 'title' => 'Insentif Bulanan'],
                [
                    'business_id' => $businessId,
                    'date' => Carbon::now()->toDateString(),
                    'type' => 'bonus',
                    'amount' => $bonusPay,
                    'description' => "Insentif kinerja periode berjalan $currentPeriod",
                    'created_by' => $adminUser?->id,
                ]
            );

            $payrollNumber = 'PAY-' . str_replace('-', '', $currentPeriod) . '-' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT);

            // EMP-002: Paid in current month (shows in current month Financial Report)
            if ($employee->code === 'EMP-002') {
                $payDate = Carbon::now()->subDays(2)->toDateString();
                $payroll = Payroll::updateOrCreate(
                    ['employee_id' => $employee->id, 'period' => $currentPeriod],
                    [
                        'business_id' => $businessId,
                        'payroll_number' => $payrollNumber,
                        'payment_date' => $payDate,
                        'base_salary' => $employee->base_salary,
                        'fixed_allowance' => $employee->fixed_allowance,
                        'attendance_allowance' => $attendanceAllowance,
                        'overtime_pay' => $overtimePay,
                        'bonus_pay' => $bonusPay,
                        'total_allowances' => $totalAllowances,
                        'absence_deduction' => 0,
                        'loan_deduction' => 0,
                        'bpjs_deduction' => $bpjs,
                        'other_deductions' => 0,
                        'total_deductions' => $totalDeductions,
                        'net_salary' => $netSalary,
                        'status' => 'paid',
                        'paid_from_account_id' => $kasUtamaAccount?->id,
                        'notes' => "Gaji kasir senior dibayarkan di awal bulan berjalan",
                        'created_by' => $adminUser?->id,
                    ]
                );

                $cashTx = CashTransaction::updateOrCreate(
                    [
                        'reference_type' => Payroll::class,
                        'reference_id' => $payroll->id,
                    ],
                    [
                        'business_id' => $businessId,
                        'type' => 'out',
                        'amount' => $netSalary,
                        'account_id' => $kasUtamaAccount?->id,
                        'category' => 'Beban Gaji & Bonus Karyawan',
                        'description' => "Pembayaran gaji {$employee->name} ({$employee->code}) Periode {$currentPeriod} [{$payrollNumber}]",
                        'transaction_date' => $payDate,
                        'created_by' => $adminUser?->id,
                    ]
                );

                $payroll->update(['cash_transaction_id' => $cashTx->id]);
            } elseif ($employee->code === 'EMP-003') {
                // EMP-003: Approved (ready to be paid by owner/admin)
                Payroll::updateOrCreate(
                    ['employee_id' => $employee->id, 'period' => $currentPeriod],
                    [
                        'business_id' => $businessId,
                        'payroll_number' => $payrollNumber,
                        'payment_date' => null,
                        'base_salary' => $employee->base_salary,
                        'fixed_allowance' => $employee->fixed_allowance,
                        'attendance_allowance' => $attendanceAllowance,
                        'overtime_pay' => $overtimePay,
                        'bonus_pay' => $bonusPay,
                        'total_allowances' => $totalAllowances,
                        'absence_deduction' => 0,
                        'loan_deduction' => 0,
                        'bpjs_deduction' => $bpjs,
                        'other_deductions' => 0,
                        'total_deductions' => $totalDeductions,
                        'net_salary' => $netSalary,
                        'status' => 'approved',
                        'notes' => 'Sudah disetujui supervisor, menunggu pencairan kas',
                        'created_by' => $adminUser?->id,
                    ]
                );
            } else {
                // EMP-001 & EMP-010: Draft (can be generated, edited, or approved)
                Payroll::updateOrCreate(
                    ['employee_id' => $employee->id, 'period' => $currentPeriod],
                    [
                        'business_id' => $businessId,
                        'payroll_number' => $payrollNumber,
                        'payment_date' => null,
                        'base_salary' => $employee->base_salary,
                        'fixed_allowance' => $employee->fixed_allowance,
                        'attendance_allowance' => $attendanceAllowance,
                        'overtime_pay' => $overtimePay,
                        'bonus_pay' => $bonusPay,
                        'total_allowances' => $totalAllowances,
                        'absence_deduction' => 0,
                        'loan_deduction' => 0,
                        'bpjs_deduction' => $bpjs,
                        'other_deductions' => 0,
                        'total_deductions' => $totalDeductions,
                        'net_salary' => $netSalary,
                        'status' => 'draft',
                        'notes' => 'Draft slip gaji bulan berjalan',
                        'created_by' => $adminUser?->id,
                    ]
                );
            }
        }
    }
}
