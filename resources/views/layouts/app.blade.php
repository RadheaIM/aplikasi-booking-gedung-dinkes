<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- CSS Aplikasi Bawaan --}}
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        
        {{-- === CSS FULLCALENDAR DARI CDN (Solusi Stabil) === --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
        
        {{-- Script utama (memuat app.js) --}}
        <script src="{{ mix('js/app.js') }}" defer></script>
        
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                {{ $slot }}
            </main>
        </div>
        
        {{-- === SCRIPT FULLCALENDAR JS DARI CDN (Wajib di sini agar dimuat terakhir) === --}}
        {{-- Dibiarkan karena mungkin masih ada kode yang mengandalkannya, meskipun Kalender utama diganti tabel. --}}
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
        
        {{-- Tempat script dari View lain dijalankan --}}
        @stack('scripts')
        
        {{-- === FOOTER COPYRIGHT BARU === --}}
        <footer class="bg-white border-t border-gray-200 mt-8 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Booking Gedung Dinkes Kabupaten Garut.
            </div>
        </footer>
        {{-- ============================== --}}
        
    </body>
</html>