<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Slip Gaji - {{ $payroll->payroll_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-size: 12px; }
            .print-container { box-shadow: none !important; border: 1px solid #ccc !important; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-6 px-3 text-gray-800">
    <div class="max-w-md mx-auto print-container bg-white p-6 rounded-2xl shadow-md border border-gray-200">
        {{-- Header Slip --}}
        <div class="text-center pb-4 border-b border-gray-200">
            <h1 class="text-base font-extrabold text-gray-900 uppercase tracking-wider">{{ $companyName }}</h1>
            @if($companyAddress)
                <p class="text-xs text-gray-500">{{ $companyAddress }}</p>
            @endif
            @if($companyPhone)
                <p class="text-xs text-gray-500">Telp: {{ $companyPhone }}</p>
            @endif
            <div class="mt-2 inline-block px-3 py-1 bg-gray-100 rounded-full text-xs font-bold text-gray-700 uppercase">
                Slip Gaji Karyawan
            </div>
        </div>

        {{-- Employee Details --}}
        <div class="py-3 border-b border-gray-100 text-xs space-y-1">
            <div class="flex justify-between">
                <span class="text-gray-500">No. Slip:</span>
                <span class="font-bold text-gray-800">{{ $payroll->payroll_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Periode:</span>
                <span class="font-bold text-gray-800">{{ $payroll->period }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Nama:</span>
                <span class="font-bold text-gray-800">{{ $payroll->employee->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Jabatan:</span>
                <span class="font-bold text-gray-800">{{ $payroll->employee->position }} ({{ $payroll->employee->code }})</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status Pembayaran:</span>
                <span class="font-bold uppercase {{ $payroll->status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ $payroll->status === 'paid' ? 'LUNAS (PAID)' : 'DRAFT' }}
                </span>
            </div>
        </div>

        {{-- Penghasilan --}}
        <div class="py-3 border-b border-gray-100 text-xs">
            <p class="font-bold text-gray-700 uppercase tracking-wider mb-2">Penghasilan</p>
            <div class="space-y-1">
                <div class="flex justify-between">
                    <span class="text-gray-600">Gaji Pokok</span>
                    <span class="font-semibold">Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}</span>
                </div>
                @if($payroll->fixed_allowance > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tunjangan Tetap</span>
                        <span class="font-semibold">Rp {{ number_format($payroll->fixed_allowance, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($payroll->attendance_allowance > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Uang Makan / Kehadiran</span>
                        <span class="font-semibold">Rp {{ number_format($payroll->attendance_allowance, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($payroll->overtime_pay > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Upah Lembur</span>
                        <span class="font-semibold">Rp {{ number_format($payroll->overtime_pay, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($payroll->bonus_pay > 0)
                    <div class="flex justify-between text-emerald-700 font-semibold">
                        <span>Bonus & Komisi</span>
                        <span>Rp {{ number_format($payroll->bonus_pay, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between pt-1 border-t border-gray-100 font-bold text-gray-900">
                    <span>Total Penghasilan (A)</span>
                    <span>Rp {{ number_format($payroll->total_allowances, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Potongan --}}
        <div class="py-3 border-b border-gray-200 text-xs">
            <p class="font-bold text-gray-700 uppercase tracking-wider mb-2">Potongan</p>
            <div class="space-y-1">
                @if($payroll->absence_deduction > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Potongan Absensi</span>
                        <span class="font-semibold text-red-600">Rp {{ number_format($payroll->absence_deduction, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($payroll->loan_deduction > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kasbon / Pinjaman</span>
                        <span class="font-semibold text-red-600">Rp {{ number_format($payroll->loan_deduction, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($payroll->bpjs_deduction > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">BPJS / Asuransi</span>
                        <span class="font-semibold text-red-600">Rp {{ number_format($payroll->bpjs_deduction, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($payroll->other_deductions > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Potongan Lain</span>
                        <span class="font-semibold text-red-600">Rp {{ number_format($payroll->other_deductions, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between pt-1 border-t border-gray-100 font-bold text-red-700">
                    <span>Total Potongan (B)</span>
                    <span>Rp {{ number_format($payroll->total_deductions, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Take Home Pay (Gaji Bersih) --}}
        <div class="py-4 bg-emerald-50 rounded-xl px-3 my-3 text-center">
            <p class="text-xs text-emerald-800 font-medium">GAJI BERSIH (TAKE HOME PAY)</p>
            <p class="text-xl font-black text-emerald-700 mt-1">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</p>
        </div>

        {{-- Footer Signatures --}}
        <div class="grid grid-cols-2 gap-4 text-center text-xs text-gray-500 pt-6">
            <div>
                <p>Penerima,</p>
                <div class="h-14"></div>
                <p class="font-bold text-gray-800 border-t border-gray-300 pt-1">{{ $payroll->employee->name }}</p>
            </div>
            <div>
                <p>Pengelola / Kasir,</p>
                <div class="h-14"></div>
                <p class="font-bold text-gray-800 border-t border-gray-300 pt-1">{{ $payroll->creator?->name ?? 'Management' }}</p>
            </div>
        </div>

        {{-- Action Buttons (No Print) --}}
        <div class="no-print mt-6 flex gap-2">
            <button onclick="window.print()" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow flex items-center justify-center gap-1.5">
                <span>🖨️ Cetak Slip Gaji</span>
            </button>
            <button onclick="window.close()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold">
                Tutup
            </button>
        </div>
    </div>
</body>
</html>
