@php
    $isAuth = Auth::check();
@endphp

@if($isAuth)
    <x-app-layout>
        <x-slot name="header">
            <x-page-header 
                title="Buku Panduan & Dokumentasi"
                subtitle="Tata cara operasional lengkap, panduan modul ERP, dan SOP kasir KarsaERP"
            />
        </x-slot>

        @include('docs.content')
    </x-app-layout>
@else
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dokumentasi & Panduan Penggunaan - KarsaERP</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-800 bg-gray-50">
        {{-- Top Bar for Guests --}}
        <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-gray-200/80 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-black text-xs shadow-md shadow-primary-500/20">
                        ERP
                    </div>
                    <div>
                        <h1 class="font-bold text-sm text-dark leading-tight">KarsaERP Docs</h1>
                        <p class="text-[10px] text-gray-400 font-medium">Buku Panduan Penggunaan Sistem</p>
                    </div>
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}" class="btn-primary text-xs py-2 px-4 rounded-lg font-bold">
                        Masuk ke Sistem &rarr;
                    </a>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @include('docs.content')
        </main>
    </body>
    </html>
@endif
