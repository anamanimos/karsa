<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white text-gray-600 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl font-bold text-dark">{{ $user->name }}</h2>
                        @if($user->is_active)
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                        @else
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-red-100 text-red-700">Nonaktif</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">ID Pengguna: #{{ $user->id }} • Terdaftar sejak {{ $user->created_at->translatedFormat('d F Y H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary text-xs py-2 px-3 flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Profil
                </a>
                @if(Auth::id() !== $user->id)
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-xs py-2 px-3 rounded-xl font-medium transition {{ $user->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">
                            {{ $user->is_active ? 'Bekukan Akun' : 'Aktifkan Akun' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline confirm-delete" data-confirm="Apakah Anda yakin ingin menghapus akun '{{ $user->name }}'? Seluruh data usaha milik pengguna ini mungkin akan terdampak!">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger text-xs py-2 px-3 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Profile & Overview Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="glass-card p-5 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-primary-500 to-emerald-400 flex items-center justify-center text-white font-bold text-xl shadow-md">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-dark">{{ $user->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-3 space-y-2 text-xs">
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="text-gray-500">Peran Sistem</span>
                        @if($user->role === 'superadmin')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700">Super Admin</span>
                        @elseif($user->role === 'kasir')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">Kasir</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">Owner / User</span>
                        @endif
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="text-gray-500">No. WhatsApp</span>
                        <span class="font-semibold text-dark">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="text-gray-500">Bisnis Aktif</span>
                        <span class="font-semibold text-primary-600">{{ $user->activeBusiness->name ?? 'Belum Diatur' }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-gray-500">Terakhir Update</span>
                        <span class="text-gray-600">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-4">
                {{-- Owned Businesses --}}
                <div class="glass-card p-5">
                    <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-2">
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-500">🏢 Bisnis Milik Pengguna (Owned Tenants)</h3>
                        <span class="text-xs font-bold text-primary-600">{{ $user->ownedBusinesses->count() }} Usaha</span>
                    </div>

                    @if($user->ownedBusinesses->isEmpty())
                        <p class="text-xs text-gray-400 py-3 text-center italic">Pengguna ini belum membuat atau memiliki unit usaha tersendiri.</p>
                    @else
                        <div class="space-y-2">
                            @foreach($user->ownedBusinesses as $biz)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/70 hover:bg-gray-100/70 transition border border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                                            🏢
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-dark">{{ $biz->name }}</h4>
                                            <p class="text-[10px] text-gray-500">{{ $biz->business_type ?? 'Retail' }} • {{ $biz->city ?? 'Indonesia' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($biz->is_active)
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                                        @else
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-red-100 text-red-700">Nonaktif</span>
                                        @endif
                                        <a href="{{ route('admin.businesses.show', $biz) }}" class="text-xs text-primary-600 hover:text-primary-700 font-semibold ml-2">
                                            Detail &rarr;
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Member of Businesses --}}
                <div class="glass-card p-5">
                    <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-2">
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-500">👥 Keanggotaan Bisnis (Assigned Branches/Stores)</h3>
                        <span class="text-xs font-bold text-purple-600">{{ $user->businesses->count() }} Terhubung</span>
                    </div>

                    @if($user->businesses->isEmpty())
                        <p class="text-xs text-gray-400 py-3 text-center italic">Tidak ada keanggotaan bisnis eksternal tambahan.</p>
                    @else
                        <div class="space-y-2">
                            @foreach($user->businesses as $assignedBiz)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/70 border border-gray-100">
                                    <div>
                                        <h4 class="text-xs font-bold text-dark">{{ $assignedBiz->name }}</h4>
                                        <p class="text-[10px] text-gray-500">Pemilik: {{ $assignedBiz->owner->name ?? '-' }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 text-[10px] font-medium rounded-full bg-gray-200 text-gray-700">
                                            Peran: {{ $assignedBiz->pivot->role ?? 'staff' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
