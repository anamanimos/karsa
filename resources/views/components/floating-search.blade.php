@props([
    'action',
    'placeholder' => 'Cari...',
    'value' => request('search', ''),
    'bottom' => 'bottom-24',
    'inputName' => 'search',
])

<div class="fixed {{ $bottom }} left-0 right-0 z-40 px-5 pointer-events-none" 
     x-data="{ 
         openFloatingSearch: false, 
         searchQuery: '{{ addslashes($value) }}' 
     }">
    <div class="max-w-7xl mx-auto relative flex justify-end h-12 sm:px-6 lg:px-8">
        <div class="absolute right-0 sm:right-6 lg:right-8 top-0 bg-white/95 backdrop-blur-md border border-gray-200/80 shadow-lg rounded-xl overflow-hidden transition-all duration-300 ease-out pointer-events-auto"
             :class="openFloatingSearch ? 'w-full sm:max-w-md h-12' : 'w-12 h-12'">
             
            {{-- Collapsed Button --}}
            <button type="button" x-show="!openFloatingSearch"
                    @click="openFloatingSearch = true; $nextTick(() => $refs.floatSearchInput.focus())"
                    class="w-full h-full flex items-center justify-center text-primary-600 active:scale-95 transition-transform duration-150"
                    title="{{ $placeholder }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle opacity="0.3" cx="11" cy="11" r="7" fill="currentColor"/>
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                    <path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </button>
             
            {{-- Expanded Form --}}
            <form method="GET" action="{{ $action }}" x-show="openFloatingSearch"
                  class="w-full h-full flex items-center px-4 gap-2.5" style="display: none;">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="{{ $inputName }}" x-model="searchQuery" x-ref="floatSearchInput"
                       placeholder="{{ $placeholder }}"
                       @keydown.escape="openFloatingSearch = false"
                       class="flex-1 bg-transparent border-0 outline-none text-xs font-semibold text-gray-700 placeholder-gray-400 focus:ring-0 p-0">
                <button type="submit" class="text-xs text-primary-600 hover:text-primary-700 font-bold flex-shrink-0 px-1 py-1">Cari</button>
                <button type="button" 
                        @click="openFloatingSearch = false; searchQuery = '{{ addslashes($value) }}'"
                        class="text-xs text-gray-400 hover:text-gray-600 font-bold flex-shrink-0 px-1 py-1">
                    Batal
                </button>
            </form>
        </div>
    </div>
</div>
