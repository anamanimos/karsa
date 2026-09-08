@extends('layouts.app')

@section('title', 'Presensi & Kehadiran')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Presensi Karyawan</h2>
            <p class="text-xs text-gray-500">Catat hari kerja, kehadiran, izin, dan lembur</p>
        </div>
        <a href="{{ route('employees.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold">
            &larr; SDM
        </a>
    </div>

    {{-- Sub Navigation Tabs (Clean Segmented Bar, Hidden Scrollbar) --}}
    <x-employee-subnav />

    {{-- Period Filter --}}
    <form method="GET" action="{{ route('employee-attendances.index') }}" class="glass-card-solid p-3 rounded-lg border border-gray-150 flex items-center justify-between gap-2">
        <label class="text-xs font-bold text-gray-700">Periode Bulan:</label>
        <div class="flex gap-2">
            <input type="month" name="period" value="{{ $period }}" class="text-xs rounded-lg border border-gray-200 px-3 py-1.5 bg-white">
            <button type="submit" class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold rounded-lg shadow-xs">Tampilkan</button>
        </div>
    </form>

    {{-- Bulk Attendance Form --}}
    <form method="POST" action="{{ route('employee-attendances.store') }}" class="space-y-3">
        @csrf
        <input type="hidden" name="period" value="{{ $period }}">

        <div class="space-y-3">
            @forelse($employees as $index => $emp)
                @php
                    $att = $emp->attendances->first();
                @endphp
                <div class="glass-card-solid p-3.5 rounded-lg border border-gray-150 shadow-xs space-y-2">
                    <input type="hidden" name="attendances[{{ $index }}][employee_id]" value="{{ $emp->id }}">
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-gray-800">{{ $emp->name }}</h3>
                            <p class="text-[10px] text-gray-400">{{ $emp->position }} ({{ $emp->code }})</p>
                        </div>
                        <span class="text-xs font-semibold text-primary-700 bg-primary-50 px-2 py-0.5 rounded-md border border-primary-100">
                            Makan: Rp {{ number_format($emp->daily_allowance, 0) }}/hari
                        </span>
                    </div>

                    <div class="grid grid-cols-4 gap-1.5 text-center text-xs">
                        <div>
                            <label class="text-[10px] text-gray-500 font-semibold block">Hari Kerja</label>
                            <input type="number" name="attendances[{{ $index }}][work_days]" value="{{ old("attendances.$index.work_days", $att?->work_days ?? 26) }}" min="0" max="31" class="w-full text-center text-xs rounded-lg border border-gray-200 py-1.5 font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] text-emerald-600 font-semibold block">Hadir (Hari)</label>
                            <input type="number" name="attendances[{{ $index }}][present_days]" value="{{ old("attendances.$index.present_days", $att?->present_days ?? 26) }}" min="0" max="31" class="w-full text-center text-xs rounded-xl border border-emerald-300 py-1.5 bg-emerald-50/50 font-bold text-emerald-800">
                        </div>
                        <div>
                            <label class="text-[10px] text-amber-600 font-semibold block">Izin / Sakit</label>
                            <input type="number" name="attendances[{{ $index }}][permission_days]" value="{{ old("attendances.$index.permission_days", $att?->permission_days ?? 0) }}" min="0" max="31" class="w-full text-center text-xs rounded-lg border border-gray-200 py-1.5">
                        </div>
                        <div>
                            <label class="text-[10px] text-red-500 font-semibold block">Alpa / Bolos</label>
                            <input type="number" name="attendances[{{ $index }}][absent_days]" value="{{ old("attendances.$index.absent_days", $att?->absent_days ?? 0) }}" min="0" max="31" class="w-full text-center text-xs rounded-lg border border-red-300 py-1.5 bg-red-50/50 text-red-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1 text-xs">
                        <div>
                            <label class="text-[10px] text-blue-600 font-semibold">Jam Lembur</label>
                            <input type="number" step="0.5" name="attendances[{{ $index }}][overtime_hours]" value="{{ old("attendances.$index.overtime_hours", $att?->overtime_hours ?? 0) }}" min="0" placeholder="0" class="w-full text-xs rounded-lg border border-gray-200 px-2 py-1">
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-500 font-semibold">Catatan</label>
                            <input type="text" name="attendances[{{ $index }}][notes]" value="{{ old("attendances.$index.notes", $att?->notes) }}" placeholder="Keterangan..." class="w-full text-xs rounded-lg border border-gray-200 px-2 py-1">
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-card-solid p-6 rounded-lg text-center text-gray-400">
                    <p class="text-sm">Tidak ada karyawan aktif.</p>
                </div>
            @endforelse
        </div>

        @if($employees->isNotEmpty())
            <div class="sticky bottom-20 z-20 pt-2">
                <button type="submit" class="w-full py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-md transition-all flex items-center justify-center gap-2">
                    <span>💾 Simpan Presensi Periode {{ $period }}</span>
                </button>
            </div>
        @endif
    </form>
</div>
@endsection
