<x-guest-layout maxWidth="max-w-lg">
    <div x-data="{
            step: {{ $errors->has('business_name') || $errors->has('business_type') ? 3 : ($errors->has('password') || $errors->has('password_confirmation') ? 2 : 1) }},
            totalSteps: 3,
            name: '{{ old('name', '') }}',
            email: '{{ old('email', '') }}',
            phone: '{{ old('phone', '') }}',
            password: '',
            password_confirmation: '',
            business_name: '{{ old('business_name', '') }}',
            business_type: '{{ old('business_type', 'general') }}',
            showPassword: false,
            errorMessage: '',

            businessTypes: [
                { id: 'general', name: 'UMKM & Serbaguna', icon: '🏪', desc: 'Kelontong & dagang umum' },
                { id: 'retail', name: 'Retail & Minimarket', icon: '🛒', desc: 'Minimarket & aneka toko' },
                { id: 'fnb', name: 'F&B & Kuliner', icon: '☕', desc: 'Resto, cafe & kedai' },
                { id: 'service', name: 'Jasa & Bengkel', icon: '🔧', desc: 'Bengkel, salon & rental' },
                { id: 'agriculture', name: 'Pertanian & Ternak', icon: '🌾', desc: 'Toko tani, pupuk & pakan' },
                { id: 'wholesale', name: 'Grosir & Distributor', icon: '📦', desc: 'Grosir & agen partai besar' },
            ],

            get selectedBusinessTypeLabel() {
                const found = this.businessTypes.find(b => b.id === this.business_type);
                return found ? found.name : 'UMKM & Serbaguna';
            },

            validateStep1() {
                this.errorMessage = '';
                if (!this.name.trim()) {
                    this.errorMessage = 'Silakan masukkan nama lengkap pemilik akun.';
                    return false;
                }
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.email.trim() || !emailRegex.test(this.email.trim())) {
                    this.errorMessage = 'Silakan masukkan alamat email yang valid.';
                    return false;
                }
                return true;
            },

            goToStep2() {
                if (this.validateStep1()) {
                    this.step = 2;
                }
            },

            validateStep2() {
                this.errorMessage = '';
                if (!this.password || this.password.length < 8) {
                    this.errorMessage = 'Kata sandi minimal 8 karakter.';
                    return false;
                }
                if (this.password !== this.password_confirmation) {
                    this.errorMessage = 'Konfirmasi kata sandi tidak cocok dengan kata sandi.';
                    return false;
                }
                return true;
            },

            goToStep3() {
                if (this.validateStep2()) {
                    this.step = 3;
                }
            },

            submitForm(e) {
                this.errorMessage = '';
                if (!this.validateStep1()) {
                    this.step = 1;
                    e.preventDefault();
                    return;
                }
                if (!this.validateStep2()) {
                    this.step = 2;
                    e.preventDefault();
                    return;
                }
                if (!this.business_name.trim()) {
                    this.errorMessage = 'Silakan isi nama usaha / unit bisnis pertama Anda.';
                    this.step = 3;
                    e.preventDefault();
                    return;
                }
            }
         }"
         class="glass-card p-5 sm:p-6 rounded-2xl border border-gray-200/80 shadow-md space-y-4">

        {{-- ================= SLIM STEPPER PROGRESS HEADER ================= --}}
        <div>
            <div class="flex items-center justify-between text-xs mb-2">
                <span class="font-bold text-dark flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-primary-600 text-white text-[11px] font-bold flex items-center justify-center" x-text="step"></span>
                    <span x-text="step === 1 ? 'Data Pemilik' : (step === 2 ? 'Keamanan' : 'Informasi Usaha')"></span>
                </span>
                <span class="text-gray-400 text-[11px]">
                    Langkah <strong class="text-dark" x-text="step"></strong> dari 3
                </span>
            </div>
            {{-- Slim Progress Bar --}}
            <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-gradient-to-r from-primary-500 to-emerald-500 h-1.5 rounded-full transition-all duration-300"
                     :style="`width: ${step === 1 ? '33.33%' : (step === 2 ? '66.66%' : '100%')}`"></div>
            </div>
        </div>

        {{-- Client-side Error Banner --}}
        <div x-show="errorMessage" 
             x-transition 
             class="p-2.5 bg-red-50 border border-red-200 rounded-xl flex items-center justify-between gap-2 text-xs text-red-700 font-semibold"
             style="display: none;">
            <div class="flex items-center gap-1.5">
                <span>⚠️</span>
                <span x-text="errorMessage"></span>
            </div>
            <button type="button" @click="errorMessage = ''" class="text-red-400 hover:text-red-600 font-bold px-1">✕</button>
        </div>

        {{-- Server-side Error Alert --}}
        @if ($errors->any())
            <div class="p-2.5 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 space-y-1">
                <div class="flex items-center gap-1.5 font-bold">
                    <span>⚠️</span>
                    <span>Terdapat kendala pada formulir:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-[11px] pl-1 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ================= REGISTRATION FORM ================= --}}
        <form method="POST" action="{{ route('register') }}" @submit="submitForm($event)" novalidate class="space-y-4">
            @csrf

            {{-- ================= STEP 1: PROFIL PEMILIK ================= --}}
            <div x-show="step === 1" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-3.5">

                <!-- Name -->
                <div class="space-y-1">
                    <label for="name" class="block text-xs font-bold text-gray-700">
                        Nama Lengkap Pemilik <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <input id="name" 
                               class="form-input-glass text-xs pl-9" 
                               type="text" 
                               name="name" 
                               x-model="name"
                               value="{{ old('name') }}" 
                               autocomplete="name" 
                               autofocus
                               placeholder="Contoh: Budi Santoso" />
                        <div class="absolute left-3 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Email Address -->
                <div class="space-y-1">
                    <label for="email" class="block text-xs font-bold text-gray-700">
                        Alamat Email Login <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <input id="email" 
                               class="form-input-glass text-xs pl-9" 
                               type="email" 
                               name="email" 
                               x-model="email"
                               value="{{ old('email') }}" 
                               autocomplete="username" 
                               placeholder="budi@email.com" />
                        <div class="absolute left-3 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400">Digunakan untuk login ke platform KarsaERP</p>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Phone -->
                <div class="space-y-1">
                    <label for="phone" class="block text-xs font-bold text-gray-700">
                        Nomor WhatsApp / HP <span class="text-gray-400 text-[10px] font-normal">(Opsional)</span>
                    </label>
                    <div class="relative flex items-center">
                        <input id="phone" 
                               class="form-input-glass text-xs pl-9" 
                               type="tel" 
                               name="phone" 
                               x-model="phone"
                               value="{{ old('phone') }}" 
                               placeholder="0812-3456-7890" />
                        <div class="absolute left-3 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>

                {{-- Step 1 CTA --}}
                <div class="pt-2 space-y-2.5">
                    <button type="button" 
                            @click="goToStep2()" 
                            class="btn-primary w-full py-3 text-sm font-bold shadow-float flex items-center justify-center gap-2 cursor-pointer">
                        <span>Lanjut ke Keamanan Akun</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                    <div class="text-center pt-1">
                        <p class="text-xs text-gray-500">
                            Sudah memiliki akun? 
                            <a href="{{ route('login') }}" class="font-bold text-primary-600 hover:text-primary-700 hover:underline">
                                Masuk di Sini
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- ================= STEP 2: KEAMANAN & SANDI ================= --}}
            <div x-show="step === 2" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;"
                 class="space-y-3.5">

                <!-- Password -->
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-bold text-gray-700">
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <button type="button" @click="showPassword = !showPassword" class="text-[11px] text-primary-600 hover:text-primary-800 font-semibold">
                            <span x-text="showPassword ? 'Sembunyikan' : 'Tampilkan Sandi'"></span>
                        </button>
                    </div>
                    <div class="relative flex items-center">
                        <input id="password" 
                               class="form-input-glass text-xs pl-9" 
                               :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               x-model="password"
                               autocomplete="new-password" 
                               placeholder="Minimal 8 karakter" />
                        <div class="absolute left-3 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Password Confirmation -->
                <div class="space-y-1">
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700">
                        Ulangi Kata Sandi <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <input id="password_confirmation" 
                               class="form-input-glass text-xs pl-9" 
                               :type="showPassword ? 'text' : 'password'" 
                               name="password_confirmation" 
                               x-model="password_confirmation"
                               autocomplete="new-password" 
                               placeholder="Ketik ulang kata sandi di atas" />
                        <div class="absolute left-3 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                {{-- Live Password Checklist --}}
                <div class="p-2.5 bg-gray-50/80 rounded-xl border border-gray-100 flex items-center justify-between text-[11px]">
                    <div class="flex items-center gap-1.5 font-medium"
                         :class="password.length >= 8 ? 'text-emerald-700' : 'text-gray-400'">
                        <span class="w-3.5 h-3.5 rounded-full flex items-center justify-center text-[9px]"
                              :class="password.length >= 8 ? 'bg-emerald-100 text-emerald-700 font-bold' : 'bg-gray-200 text-gray-400'">
                            ✓
                        </span>
                        <span>Min. 8 karakter</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-medium"
                         :class="password.length >= 8 && password === password_confirmation ? 'text-emerald-700' : 'text-gray-400'">
                        <span class="w-3.5 h-3.5 rounded-full flex items-center justify-center text-[9px]"
                              :class="password.length >= 8 && password === password_confirmation ? 'bg-emerald-100 text-emerald-700 font-bold' : 'bg-gray-200 text-gray-400'">
                            ✓
                        </span>
                        <span>Sandi cocok</span>
                    </div>
                </div>

                {{-- Step 2 CTA --}}
                <div class="flex items-center gap-2.5 pt-2">
                    <button type="button" 
                            @click="step = 1; errorMessage = '';" 
                            class="btn-secondary w-1/3 py-3 text-xs font-bold flex items-center justify-center gap-1 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali</span>
                    </button>

                    <button type="button" 
                            @click="goToStep3()" 
                            class="btn-primary w-2/3 py-3 text-sm font-bold shadow-float flex items-center justify-center gap-2 cursor-pointer">
                        <span>Lanjut ke Info Usaha</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ================= STEP 3: PROFIL USAHA & REVIEW ================= --}}
            <div x-show="step === 3" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;"
                 class="space-y-3.5">

                <!-- Business Name -->
                <div class="space-y-1">
                    <label for="business_name" class="block text-xs font-bold text-gray-700">
                        Nama Usaha / Toko Pertama <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <input id="business_name" 
                               class="form-input-glass text-xs pl-9" 
                               type="text" 
                               name="business_name" 
                               x-model="business_name"
                               value="{{ old('business_name') }}" 
                               placeholder="Contoh: Toko Berkah / Cafe Kopi Senja" />
                        <div class="absolute left-3 text-gray-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('business_name')" class="mt-1" />
                </div>

                <!-- Business Type (Visual Card Selector) -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-gray-700">
                            Model Bisnis <span class="text-red-500">*</span>
                        </label>
                        <span class="text-[10px] text-primary-600 font-bold" x-text="selectedBusinessTypeLabel"></span>
                    </div>

                    {{-- Hidden real input for standard HTML post --}}
                    <input type="hidden" name="business_type" :value="business_type">

                    {{-- Visual Radio Cards Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <template x-for="b in businessTypes" :key="b.id">
                            <button type="button" 
                                    @click="business_type = b.id"
                                    class="p-2.5 rounded-xl border text-left transition-all duration-150 cursor-pointer flex flex-col justify-between"
                                    :class="business_type === b.id 
                                        ? 'border-primary-500 bg-primary-50/70 ring-1 ring-primary-500 shadow-2xs' 
                                        : 'border-gray-200 bg-white/70 hover:border-gray-300 hover:bg-gray-50/60'">
                                <div class="flex items-center justify-between w-full mb-0.5">
                                    <span class="text-base" x-text="b.icon"></span>
                                    <span class="w-3.5 h-3.5 rounded-full flex items-center justify-center text-[9px]"
                                          :class="business_type === b.id ? 'bg-primary-600 text-white font-bold' : 'border border-gray-300 text-transparent'">
                                        ✓
                                    </span>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold leading-tight truncate" 
                                       :class="business_type === b.id ? 'text-primary-800' : 'text-dark'" 
                                       x-text="b.name"></p>
                                    <p class="text-[9px] text-gray-400 mt-0.5 truncate" x-text="b.desc"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                    <x-input-error :messages="$errors->get('business_type')" class="mt-1" />
                </div>

                {{-- Compact Review Summary Pill --}}
                <div class="p-2.5 bg-emerald-50/70 rounded-xl border border-emerald-200/70 flex items-center justify-between text-[11px]">
                    <div class="flex items-center gap-2 truncate">
                        <span class="text-base flex-shrink-0">🏢</span>
                        <div class="truncate">
                            <span class="font-bold text-dark" x-text="business_name || 'Nama Toko'"></span>
                            <span class="text-emerald-700 block text-[10px]" x-text="name + ' • ' + selectedBusinessTypeLabel"></span>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-emerald-600 text-white flex-shrink-0">
                        Starter Gratis
                    </span>
                </div>

                {{-- Step 3 CTA --}}
                <div class="flex items-center gap-2.5 pt-2">
                    <button type="button" 
                            @click="step = 2; errorMessage = '';" 
                            class="btn-secondary w-1/3 py-3 text-xs font-bold flex items-center justify-center gap-1 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali</span>
                    </button>

                    <button type="submit" 
                            class="btn-primary w-2/3 py-3 text-sm font-bold shadow-float flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>🚀 Daftar & Mulai</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>
