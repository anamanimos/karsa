<x-app-layout>
    <x-slot name="header">
        <x-page-header 
            title="Manajemen Karyawan"
            :searchAction="route('employees.index')"
            searchPlaceholder="Cari nama, NIK, jabatan, no. HP..."
            :createRoute="$stats['can_add'] ? route('employees.create') : null"
            :createLabel="$stats['can_add'] ? 'Tambah Karyawan' : null"
        >
            <x-slot name="titleExtra">
                <p class="text-xs text-gray-500 mt-0.5">
                    Kelola data pegawai, gaji pokok, dan tunjangan
                    <span class="inline-block ml-1 font-semibold text-primary-700 bg-primary-50 px-2 py-0.5 rounded-md border border-primary-100 text-[10px]">
                        Kuota: {{ $stats['active'] }}/{{ $stats['max_employees'] >= 999999 ? '∞' : $stats['max_employees'] }} Karyawan Aktif
                    </span>
                </p>
            </x-slot>
            @if(!$stats['can_add'])
                <x-slot name="actions">
                    <button type="button" onclick="Swal.fire({title: 'Batas Karyawan Tercapai', text: 'Unit usaha ini telah mencapai batas kuota maksimal {{ $stats['max_employees'] }} karyawan aktif. Hubungi Super Admin atau upgrade paket Anda untuk menambah karyawan.', icon: 'warning', confirmButtonText: 'Mengerti'})" class="px-3 py-2 rounded-lg bg-gray-200 text-gray-500 text-xs font-bold cursor-not-allowed flex items-center gap-1.5">
                        <span>🔒 Kuota Penuh</span>
                    </button>
                </x-slot>
            @endif
        </x-page-header>
    </x-slot>

    <div class="space-y-4">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-3 gap-2 text-center">
        <div class="glass-card-solid p-3 rounded-lg border border-gray-150">
            <p class="text-[10px] text-gray-400 font-medium">Total SDM</p>
            <p class="text-base font-extrabold text-gray-800">{{ $stats['total'] }}</p>
        </div>
        <div class="glass-card-solid p-3 rounded-lg border border-gray-150">
            <p class="text-[10px] text-gray-400 font-medium">Status Aktif</p>
            <p class="text-base font-extrabold text-emerald-600">{{ $stats['active'] }}</p>
        </div>
        <div class="glass-card-solid p-3 rounded-lg border border-gray-150">
            <p class="text-[10px] text-gray-400 font-medium">Total Gaji Pokok</p>
            <p class="text-xs font-extrabold text-primary-700 mt-1">Rp {{ number_format($stats['total_base_payroll'] / 1000, 0) }}k</p>
        </div>
    </div>

    {{-- Sub Navigation Tabs (Clean Segmented Bar, Hidden Scrollbar) --}}
    <x-employee-subnav />

    {{-- Active Search Indicator --}}
    <x-active-search-indicator :resetUrl="route('employees.index')" />

    {{-- Employees List --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pb-12">
        @forelse($employees as $emp)
            <div class="glass-card-solid p-3.5 rounded-lg border border-gray-150 shadow-xs flex flex-col justify-between gap-2">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-gray-800">{{ $emp->name }}</h3>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold {{ $emp->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $emp->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        <p class="text-xs text-primary-700 font-medium">{{ $emp->position }} &bull; <span class="text-gray-400">{{ $emp->code }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-800">Rp {{ number_format($emp->base_salary, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-gray-400">Gaji Pokok</p>
                    </div>
                </div>

                <div class="flex items-center justify-between text-[11px] text-gray-500 pt-2 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <span>📞 {{ $emp->phone ?? '-' }}</span>
                        @if($emp->bank_name)
                            <span>💳 {{ $emp->bank_name }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('employees.show', $emp) }}" class="text-primary-600 font-bold hover:underline">Detail</a>
                        <a href="{{ route('employees.edit', $emp) }}" class="text-amber-600 font-bold hover:underline">Edit</a>
                        <form action="{{ route('employees.destroy', $emp) }}" method="POST" class="inline confirm-delete" data-confirm="Hapus data karyawan {{ $emp->name }}?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 font-bold hover:underline">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="glass-card-solid p-6 rounded-lg text-center text-gray-400 col-span-full">
                <p class="text-sm">Belum ada data karyawan.</p>
                <a href="{{ route('employees.create') }}" class="text-xs text-primary-600 font-bold mt-2 inline-block">+ Tambah Karyawan Pertama</a>
            </div>
        @endforelse

        <div class="col-span-full">
            {{ $employees->links() }}
        </div>
    </div>

    </div>
</x-app-layout>
