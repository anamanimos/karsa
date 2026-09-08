@extends('layouts.app')

@section('title', 'Bonus & Komisi Karyawan')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Bonus & Komisi</h2>
            <p class="text-xs text-gray-500">Pemberian insentif, komisi penjualan kasir, dan THR</p>
        </div>
        <button onclick="document.getElementById('modal-bonus').classList.remove('hidden')" class="px-3.5 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-xs transition-all flex items-center gap-1.5">
            <span>+ Tambah Bonus</span>
        </button>
    </div>

    {{-- Sub Navigation Tabs (Clean Segmented Bar, Hidden Scrollbar) --}}
    <x-employee-subnav />

    {{-- Stats Summary --}}
    <div class="glass-card-solid p-3.5 rounded-lg border border-gray-150 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-400">Total Bonus Diberikan</p>
            <p class="text-base font-extrabold text-primary-700">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</p>
        </div>
        <span class="text-xs px-2.5 py-1 rounded-md bg-purple-50 text-purple-700 font-bold border border-purple-100">
            {{ $stats['total_count'] }} Catatan
        </span>
    </div>

    {{-- Bonuses List --}}
    <div class="space-y-2.5">
        @forelse($bonuses as $b)
            <div class="glass-card-solid p-3.5 rounded-lg border border-gray-150 shadow-xs flex flex-col gap-1.5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xs font-bold text-gray-800">{{ $b->title }}</h3>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-purple-50 text-purple-700 uppercase border border-purple-100">
                                {{ $b->type }}
                            </span>
                        </div>
                        <p class="text-xs font-medium text-primary-700">{{ $b->employee->name }} &bull; <span class="text-gray-400">{{ $b->date->format('d/m/Y') }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-extrabold text-emerald-600">+ Rp {{ number_format($b->amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                @if($b->description)
                    <p class="text-[11px] text-gray-500 bg-gray-50/80 p-2 rounded-lg border border-gray-100">{{ $b->description }}</p>
                @endif

                <div class="flex items-center justify-between text-[10px] text-gray-400 pt-1">
                    <span>Oleh: {{ $b->creator?->name ?? 'Admin' }}</span>
                    <form action="{{ route('employee-bonuses.destroy', $b) }}" method="POST" class="inline confirm-delete" data-confirm="Hapus catatan bonus ini?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 font-bold hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="glass-card-solid p-6 rounded-lg text-center text-gray-400">
                <p class="text-sm">Belum ada catatan bonus atau komisi.</p>
            </div>
        @endforelse

        {{ $bonuses->links() }}
    </div>

    {{-- Modal Add Bonus --}}
    <div id="modal-bonus" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-3">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-4 border border-gray-200 space-y-3" @click.outside="document.getElementById('modal-bonus').classList.add('hidden')">
            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800">Tambah Bonus / Komisi Karyawan</h3>
                <button type="button" onclick="document.getElementById('modal-bonus').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
            </div>

            <form method="POST" action="{{ route('employee-bonuses.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs font-semibold text-gray-700">Pilih Karyawan <span class="text-red-500">*</span></label>
                    <select name="employee_id" required class="w-full text-xs rounded-lg border border-gray-200 px-3 py-2 mt-1">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->position }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-lg border border-gray-200 px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Jenis Insentif <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full text-xs rounded-lg border border-gray-200 px-3 py-2 mt-1">
                            <option value="bonus">Bonus Target / Prestasi</option>
                            <option value="commission">Komisi Penjualan Kasir</option>
                            <option value="thr">Tunjangan Hari Raya (THR)</option>
                            <option value="incentive">Insentif Khusus</option>
                            <option value="other">Lain-lain</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700">Judul Bonus <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Bonus Target Penjualan Bulan Ini" class="w-full text-xs rounded-lg border border-gray-200 px-3 py-2 mt-1">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700">Nominal Bonus (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" required min="1" placeholder="250000" class="w-full text-xs rounded-lg border border-gray-200 px-3 py-2 mt-1 font-bold text-emerald-700">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700">Keterangan Tambahan</label>
                    <textarea name="description" rows="2" placeholder="Catatan opsional..." class="w-full text-xs rounded-lg border border-gray-200 px-3 py-2 mt-1"></textarea>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('modal-bonus').classList.add('hidden')" class="w-1/2 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="w-1/2 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-xs">
                        Simpan Bonus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
