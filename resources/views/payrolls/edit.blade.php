@extends('layouts.app')

@section('title', 'Penyesuaian Slip Gaji')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Edit Slip Gaji</h2>
            <p class="text-xs text-gray-500">{{ $payroll->employee->name }} &bull; Periode {{ $payroll->period }}</p>
        </div>
        <a href="{{ route('payrolls.index', ['period' => $payroll->period]) }}" class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold">
            &larr; Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('payrolls.update', $payroll) }}" class="glass-card-solid p-4 rounded-2xl border border-gray-100 shadow-sm space-y-4">
        @csrf
        @method('PUT')

        {{-- Penghasilan --}}
        <div>
            <h3 class="text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">Penghasilan (Earnings)</h3>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Gaji Pokok (Rp)</label>
                        <input type="number" name="base_salary" value="{{ old('base_salary', $payroll->base_salary) }}" required min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1 font-bold">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Tunjangan Tetap (Rp)</label>
                        <input type="number" name="fixed_allowance" value="{{ old('fixed_allowance', $payroll->fixed_allowance) }}" min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Uang Makan / Kehadiran (Rp)</label>
                        <input type="number" name="attendance_allowance" value="{{ old('attendance_allowance', $payroll->attendance_allowance) }}" min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Upah Lembur (Rp)</label>
                        <input type="number" name="overtime_pay" value="{{ old('overtime_pay', $payroll->overtime_pay) }}" min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700">Bonus & Komisi Penjualan (Rp)</label>
                    <input type="number" name="bonus_pay" value="{{ old('bonus_pay', $payroll->bonus_pay) }}" min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1 text-emerald-700 font-bold">
                </div>
            </div>
        </div>

        {{-- Potongan --}}
        <div class="pt-2 border-t border-gray-100">
            <h3 class="text-xs font-bold text-red-600 uppercase tracking-wider mb-2">Potongan (Deductions)</h3>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Potongan Absen / Alpa (Rp)</label>
                        <input type="number" name="absence_deduction" value="{{ old('absence_deduction', $payroll->absence_deduction) }}" min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1 text-red-600">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Kasbon / Pinjaman (Rp)</label>
                        <input type="number" name="loan_deduction" value="{{ old('loan_deduction', $payroll->loan_deduction) }}" min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1 text-red-600">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">BPJS / Asuransi (Rp)</label>
                        <input type="number" name="bpjs_deduction" value="{{ old('bpjs_deduction', $payroll->bpjs_deduction) }}" min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1 text-red-600">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Potongan Lain (Rp)</label>
                        <input type="number" name="other_deductions" value="{{ old('other_deductions', $payroll->other_deductions) }}" min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1 text-red-600">
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label class="text-xs font-semibold text-gray-700">Catatan Pada Slip Gaji</label>
            <input type="text" name="notes" value="{{ old('notes', $payroll->notes) }}" placeholder="Catatan opsional..." class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
        </div>

        <div class="pt-3">
            <button type="submit" class="w-full py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-md transition-all">
                Simpan & Hitung Ulang Take Home Pay
            </button>
        </div>
    </form>
</div>
@endsection
