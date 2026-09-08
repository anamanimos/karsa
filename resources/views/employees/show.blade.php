@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">{{ $employee->name }}</h2>
            <p class="text-xs text-gray-500">{{ $employee->position }} &bull; {{ $employee->code }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('employees.edit', $employee) }}" class="px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-bold border border-amber-200">
                Edit
            </a>
            <a href="{{ route('employees.index') }}" class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold">
                Kembali
            </a>
        </div>
    </div>

    {{-- Profile Summary Card --}}
    <div class="glass-card-solid p-4 rounded-2xl border border-gray-100 shadow-sm space-y-3">
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div>
                <p class="text-gray-400">Status Karyawan</p>
                <p class="font-bold text-gray-800 capitalize">{{ $employee->employment_status }} &bull; {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}</p>
            </div>
            <div>
                <p class="text-gray-400">Tanggal Bergabung</p>
                <p class="font-bold text-gray-800">{{ $employee->join_date ? $employee->join_date->format('d F Y') : '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400">Kontak WhatsApp / Telp</p>
                <p class="font-bold text-gray-800">{{ $employee->phone ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400">Rekening Gaji</p>
                <p class="font-bold text-gray-800">{{ $employee->bank_name ? $employee->bank_name . ' - ' . $employee->bank_account_number : '-' }}</p>
            </div>
        </div>

        <div class="pt-3 border-t border-gray-100 grid grid-cols-3 gap-2 text-center">
            <div class="bg-gray-50 p-2 rounded-xl">
                <p class="text-[10px] text-gray-400">Gaji Pokok</p>
                <p class="text-xs font-extrabold text-gray-800">Rp {{ number_format($employee->base_salary, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-50 p-2 rounded-xl">
                <p class="text-[10px] text-gray-400">Tunjangan Tetap</p>
                <p class="text-xs font-extrabold text-gray-800">Rp {{ number_format($employee->fixed_allowance, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-50 p-2 rounded-xl">
                <p class="text-[10px] text-gray-400">Uang Makan/Hari</p>
                <p class="text-xs font-extrabold text-gray-800">Rp {{ number_format($employee->daily_allowance, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Payroll History for this Employee --}}
    <div class="glass-card-solid p-3.5 rounded-2xl border border-gray-100 shadow-sm">
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Riwayat Penggajian Terakhir</h3>
        <div class="divide-y divide-gray-100">
            @forelse($employee->payrolls as $pay)
                <div class="py-2 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-800">Periode {{ $pay->period }}</p>
                        <p class="text-[10px] text-gray-400">{{ $pay->payroll_number }} &bull; {{ $pay->payment_date ? $pay->payment_date->format('d/m/Y') : 'Belum dibayar' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-extrabold text-primary-700">Rp {{ number_format($pay->net_salary, 0, ',', '.') }}</p>
                        <a href="{{ route('payrolls.slip', $pay) }}" class="text-[10px] text-primary-600 hover:underline">Lihat Slip &rarr;</a>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 text-center py-2">Belum ada riwayat penggajian.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
