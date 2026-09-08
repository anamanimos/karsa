<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <span class="px-2.5 py-0.5 text-[10px] font-black uppercase rounded-full bg-purple-100 text-purple-700 tracking-wider">
                    👑 Platform Super Administrator
                </span>
                <h2 class="text-xl font-bold text-dark mt-1">Manajemen Seluruh Pengguna (Platform Users)</h2>
                <p class="text-xs text-gray-500">Kelola akun pemilik usaha, kasir, dan administrator di seluruh SaaS</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary text-xs py-2.5 px-4 flex items-center gap-1.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                + Tambah Pengguna Baru
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- KPI Quick Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="glass-card p-3">
                <span class="text-[11px] text-gray-500 font-medium block">Total Pengguna</span>
                <span class="text-lg font-black text-dark mt-0.5 block">{{ $stats['total'] }}</span>
            </div>
            <div class="glass-card p-3 border-l-4 border-emerald-500">
                <span class="text-[11px] text-gray-500 font-medium block">Pemilik Usaha (Owner)</span>
                <span class="text-lg font-black text-emerald-600 mt-0.5 block">{{ $stats['users'] }}</span>
            </div>
            <div class="glass-card p-3 border-l-4 border-amber-500">
                <span class="text-[11px] text-gray-500 font-medium block">Petugas Kasir</span>
                <span class="text-lg font-black text-amber-600 mt-0.5 block">{{ $stats['cashiers'] }}</span>
            </div>
            <div class="glass-card p-3 border-l-4 border-purple-500">
                <span class="text-[11px] text-gray-500 font-medium block">Super Administrator</span>
                <span class="text-lg font-black text-purple-600 mt-0.5 block">{{ $stats['superadmins'] }}</span>
            </div>
        </div>

        {{-- Filter & Search Form --}}
        <div class="glass-card p-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Cari Pengguna</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no. telepon..." class="form-input-glass text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Peran / Role</label>
                    <select name="role" class="form-input-glass text-xs">
                        <option value="">-- Semua Peran --</option>
                        <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Owner / User</option>
                        <option value="kasir" {{ request('role') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-primary text-xs py-2.5 px-4 flex-1">
                        Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary text-xs py-2.5 px-3">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Users Table --}}
        <div class="glass-card overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-dark text-sm">Daftar Pengguna Sistem</h3>
                <span class="text-xs text-gray-500">{{ $users->total() }} Pengguna Terdaftar</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-gray-500 uppercase font-semibold text-[10px]">
                        <tr>
                            <th class="p-3">Nama & Kontak</th>
                            <th class="p-3 text-center">Peran (Role)</th>
                            <th class="p-3">Usaha Dimiliki / Ditugaskan</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-center">Terdaftar</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-dark">{{ $user->name }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $user->email }} @if($user->phone) • {{ $user->phone }} @endif</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3 text-center">
                                    <span class="px-2.5 py-0.5 text-[10px] font-extrabold rounded-full {{ $user->role === 'superadmin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'user' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ strtoupper($user->role) }}
                                    </span>
                                    @if($user->plan)
                                        <div class="mt-1">
                                            <span class="px-2 py-0.5 text-[9px] font-bold rounded-md bg-purple-50 text-purple-700 border border-purple-200/60 inline-block" title="Batas: {{ $user->maxBusinesses() >= 999999 ? '∞' : $user->maxBusinesses() }} Toko, {{ $user->maxEmployeesPerBusiness() >= 999999 ? '∞' : $user->maxEmployeesPerBusiness() }} Karyawan">
                                                📦 {{ $user->plan->name }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-3">
                                    @if($user->ownedBusinesses->count() > 0)
                                        <span class="font-semibold text-dark block">{{ $user->ownedBusinesses->pluck('name')->join(', ') }}</span>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-[10px] text-gray-500 font-medium">
                                                {{ $user->ownedBusinesses->count() }} / {{ $user->maxBusinesses() >= 999999 ? '∞' : $user->maxBusinesses() }} Toko
                                            </span>
                                            @if($user->custom_max_businesses)
                                                <span class="text-[9px] px-1.5 py-0.2 rounded bg-amber-100 text-amber-800 font-bold" title="Custom batas toko diatur oleh Super Admin">Override Toko</span>
                                            @endif
                                        </div>
                                    @elseif($user->activeBusiness)
                                        <span class="text-gray-700">{{ $user->activeBusiness->name }} (Staff)</span>
                                    @else
                                        <span class="text-gray-400 italic">Belum ada bisnis</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $user->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                    </span>
                                </td>
                                <td class="p-3 text-center text-gray-500 whitespace-nowrap">
                                    {{ $user->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="p-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="p-1.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition" title="Lihat Profil">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="p-1.5 rounded-lg bg-primary-50 text-primary-700 hover:bg-primary-100 transition" title="Edit Pengguna">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-1.5 rounded-lg {{ $user->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} transition" title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                    @if($user->is_active)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline confirm-delete" data-confirm="Yakin ingin menghapus pengguna {{ $user->name }}?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 transition" title="Hapus Pengguna">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-400">
                                    Tidak ada data pengguna yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
