@extends('layouts.app')

@section('title', 'Penggajian & Slip Gaji')

@section('content')
<div class="space-y-4">
    {{-- Header & Generate Button --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Penggajian Karyawan</h2>
            <p class="text-xs text-gray-500">Hitung otomatis gaji, tunjangan, lembur, dan potongan</p>
        </div>
        <form method="POST" action="{{ route('payrolls.generate') }}" class="inline">
            @csrf
            <input type="hidden" name="period" value="{{ $period }}">
            <button type="submit" class="px-3.5 py-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-xs transition-all flex items-center gap-1.5">
                <span>⚡ Generate Gaji</span>
            </button>
        </form>
    </div>

    {{-- Sub Navigation Tabs (Clean Segmented Bar, Hidden Scrollbar) --}}
    <x-employee-subnav />

    {{-- Period Filter & Stats Summary --}}
    <form method="GET" action="{{ route('payrolls.index') }}" class="glass-card-solid p-3 rounded-lg border border-gray-150 flex items-center justify-between gap-2">
        <label class="text-xs font-bold text-gray-700">Periode:</label>
        <div class="flex gap-2">
            <input type="month" name="period" value="{{ $period }}" class="text-xs rounded-lg border border-gray-200 px-3 py-1.5 bg-white">
            <button type="submit" class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold rounded-lg shadow-xs">Tampilkan</button>
        </div>
    </form>

    <div class="grid grid-cols-3 gap-2 text-center">
        <div class="glass-card-solid p-3 rounded-lg border border-gray-150">
            <p class="text-[10px] text-gray-400 font-medium">Total Beban Gaji</p>
            <p class="text-xs font-extrabold text-gray-800 mt-0.5">Rp {{ number_format($stats['total_net_salary'] / 1000, 0) }}k</p>
        </div>
        <div class="glass-card-solid p-3 rounded-lg border border-gray-150">
            <p class="text-[10px] text-gray-400 font-medium">Sudah Dibayar</p>
            <p class="text-xs font-extrabold text-emerald-600 mt-0.5">Rp {{ number_format($stats['total_paid'] / 1000, 0) }}k</p>
        </div>
        <div class="glass-card-solid p-3 rounded-lg border border-gray-150">
            <p class="text-[10px] text-gray-400 font-medium">Belum Dibayar</p>
            <p class="text-xs font-extrabold text-amber-600 mt-0.5">Rp {{ number_format($stats['total_pending'] / 1000, 0) }}k</p>
        </div>
    </div>

    {{-- Payrolls List --}}
    <div class="space-y-3">
        @forelse($payrolls as $pay)
            <div class="glass-card-solid p-3.5 rounded-lg border border-gray-150 shadow-xs space-y-2.5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-gray-800">{{ $pay->employee->name }}</h3>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase {{ $pay->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : ($pay->status === 'approved' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ $pay->status }}
                            </span>
                        </div>
                        <p class="text-xs text-primary-700 font-medium">{{ $pay->employee->position }} &bull; <span class="text-gray-400">{{ $pay->payroll_number }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-extrabold text-dark">Rp {{ number_format($pay->net_salary, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-gray-400">Take Home Pay</p>
                    </div>
                </div>

                {{-- Earnings breakdown --}}
                <div class="bg-gray-50/70 p-2 rounded-xl grid grid-cols-3 gap-1 text-center text-[10px]">
                    <div>
                        <span class="text-gray-400 block">Gaji Pokok</span>
                        <span class="font-bold text-gray-700">Rp {{ number_format($pay->base_salary, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Tunjangan & Bonus</span>
                        <span class="font-bold text-emerald-600">+ Rp {{ number_format($pay->fixed_allowance + $pay->attendance_allowance + $pay->overtime_pay + $pay->bonus_pay, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Potongan</span>
                        <span class="font-bold text-red-500">- Rp {{ number_format($pay->total_deductions, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1 text-xs">
                    <a href="{{ route('payrolls.slip', $pay) }}" class="text-primary-600 font-bold hover:underline flex items-center gap-1">
                        <span>📄 Cetak Slip Gaji</span>
                    </a>

                    <div class="flex items-center gap-2">
                        @if($pay->status === 'draft')
                            <a href="{{ route('payrolls.edit', $pay) }}" class="text-amber-600 font-bold hover:underline">Edit</a>
                            <form action="{{ route('payrolls.approve', $pay) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-bold">Setujui</button>
                            </form>
                        @endif

                        @if($pay->status === 'approved')
                            <button onclick="openPayModal('{{ $pay->id }}', '{{ $pay->employee->name }}', '{{ number_format($pay->net_salary, 0, ',', '.') }}')" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow">
                                Bayar Gaji
                            </button>
                        @endif

                        @if($pay->status === 'paid')
                            <span class="text-[11px] text-emerald-600 font-bold">
                                Lunas ({{ $pay->paidFromAccount?->name ?? 'Kas' }})
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="glass-card-solid p-6 rounded-lg text-center text-gray-400">
                <p class="text-sm">Belum ada slip gaji untuk periode {{ $period }}.</p>
                <p class="text-xs text-gray-400 mt-1">Klik tombol <strong>⚡ Generate Gaji</strong> di atas untuk membuat slip gaji otomatis.</p>
            </div>
        @endforelse

        {{ $payrolls->links() }}
    </div>

    {{-- Modal Bayar Gaji --}}
    <div id="modal-pay" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-3">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-4 border border-gray-200 space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800">Pembayaran Gaji Karyawan</h3>
                <button type="button" onclick="document.getElementById('modal-pay').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
            </div>

            <form id="form-pay" method="POST" action="" class="space-y-3">
                @csrf
                <div class="bg-emerald-50 p-3 rounded-lg border border-emerald-100">
                    <p class="text-xs text-emerald-800 font-medium" id="pay-employee-name">Nama Karyawan</p>
                    <p class="text-lg font-extrabold text-emerald-700" id="pay-amount">Rp 0</p>
                    <p class="text-[10px] text-emerald-600 mt-0.5">Otomatis dicatat sebagai Beban Gaji di Laporan Keuangan.</p>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700">Dibayar Dari Kas / Rekening <span class="text-red-500">*</span></label>
                    <select name="account_id" required class="w-full text-xs rounded-lg border border-gray-200 px-3 py-2 mt-1">
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-lg border border-gray-200 px-3 py-2 mt-1">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700">Catatan Pembayaran</label>
                    <input type="text" name="notes" placeholder="Transfer BCA / Tunai..." class="w-full text-xs rounded-lg border border-gray-200 px-3 py-2 mt-1">
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('modal-pay').classList.add('hidden')" class="w-1/2 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="w-1/2 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs">
                        Konfirmasi Bayar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPayModal(payrollId, employeeName, amount) {
        document.getElementById('form-pay').action = '/payrolls/' + payrollId + '/pay';
        document.getElementById('pay-employee-name').innerText = 'Karyawan: ' + employeeName;
        document.getElementById('pay-amount').innerText = 'Rp ' + amount;
        document.getElementById('modal-pay').classList.remove('hidden');
    }
</script>
@endsection
