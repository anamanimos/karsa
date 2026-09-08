@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Tambah Karyawan Baru</h2>
            <p class="text-xs text-gray-500">Lengkapi data profil dan struktur gaji karyawan</p>
        </div>
        <a href="{{ route('employees.index') }}" class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold">
            &larr; Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('employees.store') }}" class="glass-card-solid p-4 rounded-2xl border border-gray-100 shadow-sm space-y-4">
        @csrf

        {{-- Data Pribadi --}}
        <div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Informasi Pribadi & Posisi</h3>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Kode / NIK <span class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code', 'EMP-' . str_pad(\App\Models\Employee::count() + 1, 3, '0', STR_PAD_LEFT)) }}" required class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Status Kerja <span class="text-red-500">*</span></label>
                        <select name="employment_status" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                            <option value="permanent">Tetap</option>
                            <option value="contract">Kontrak</option>
                            <option value="probation">Masa Percobaan</option>
                            <option value="daily">Harian</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Jabatan / Posisi <span class="text-red-500">*</span></label>
                        <input type="text" name="position" value="{{ old('position') }}" required placeholder="Kasir / Staff / Manager" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Tanggal Masuk</label>
                        <input type="text" name="join_date" value="{{ old('join_date', date('Y-m-d')) }}" class="datepicker w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">No. WhatsApp / HP</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0812..." class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@domain.com" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                    </div>
                </div>
            </div>
        </div>

        {{-- Struktur Gaji --}}
        <div class="pt-2 border-t border-gray-100">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Komponen Gaji & Tunjangan</h3>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Gaji Pokok (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="base_salary" value="{{ old('base_salary', 3000000) }}" required min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1 font-bold">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Tunjangan Tetap (Rp)</label>
                        <input type="number" name="fixed_allowance" value="{{ old('fixed_allowance', 0) }}" min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Uang Makan/Hari (Rp)</label>
                        <input type="number" name="daily_allowance" value="{{ old('daily_allowance', 25000) }}" min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Tarif Lembur/Jam (Rp)</label>
                        <input type="number" name="overtime_rate_per_hour" value="{{ old('overtime_rate_per_hour', 20000) }}" min="0" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700">Komisi Penjualan Kasir (%)</label>
                    <input type="number" step="0.1" name="commission_rate" value="{{ old('commission_rate', 0) }}" min="0" max="100" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                </div>
            </div>
        </div>

        {{-- Rekening Bank --}}
        <div class="pt-2 border-t border-gray-100">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Informasi Pembayaran Bank</h3>
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="text-xs font-semibold text-gray-700">Nama Bank</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', 'BCA') }}" placeholder="BCA/BRI/Mandiri" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                </div>
                <div class="col-span-2">
                    <label class="text-xs font-semibold text-gray-700">Nomor Rekening</label>
                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" placeholder="1234567890" class="w-full text-xs rounded-xl border border-gray-200 px-3 py-2 mt-1">
                </div>
            </div>
        </div>

        {{-- Status Aktif --}}
        <div class="flex items-center gap-2 pt-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded text-primary-600 focus:ring-primary-500">
            <label for="is_active" class="text-xs font-semibold text-gray-700">Karyawan Aktif Bekerja</label>
        </div>

        <div class="pt-3">
            <button type="submit" class="w-full py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-md transition-all">
                Simpan Data Karyawan
            </button>
        </div>
    </form>
</div>
@endsection
