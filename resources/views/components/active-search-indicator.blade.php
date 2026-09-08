@props([
    'value' => request('search', ''),
    'resetUrl' => url()->current(),
    'label' => 'Hasil pencarian',
])

@if(!empty($value))
<div class="glass-card px-3.5 py-2 flex items-center justify-between text-xs font-semibold rounded-lg border border-gray-200/60 bg-white/80 shadow-xs mb-3">
    <div class="flex items-center gap-1.5 text-gray-600">
        <svg class="w-3.5 h-3.5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <span class="text-gray-500">{{ $label }}:</span>
        <span class="bg-primary-50 text-primary-700 px-1.5 py-0.5 rounded text-[11px] font-bold">&quot;{{ $value }}&quot;</span>
    </div>
    <a href="{{ $resetUrl }}" class="text-[11px] font-bold text-red-500 hover:text-red-700 transition-colors flex items-center gap-0.5">
        <span>Reset</span> &times;
    </a>
</div>
@endif
