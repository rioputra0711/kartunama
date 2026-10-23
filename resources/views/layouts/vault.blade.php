<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Vault') — {{ config('app.name', 'KartuNama Vault') }}</title>

    {{-- Fonts: display serif + grotesque + mono. Bunny mirrors Google Fonts without tracking. --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600;fraunces:opsz,wght@9..144,400;9..144,600;9..144,700;jetbrains-mono:400,500&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /*! tailwindcss v4 fallback — build assets with `npm run build` */@layer theme{:root,:host{--font-sans:'Instrument Sans',ui-sans-serif,system-ui,sans-serif;--font-display:'Fraunces',ui-serif,Georgia,serif;--font-mono:'JetBrains Mono',ui-monospace,monospace;--color-ink-950:#0b0a08;--color-ink-900:#14120e;--color-ink-800:#221e18;--color-ink-700:#2e2920;--color-ink-600:#3d372c;--color-ink-500:#574f40;--color-ink-400:#7d7360;--color-ink-300:#a89c85;--color-ink-200:#cfc4ae;--color-ink-100:#e6ddca;--color-paper:#ede7da;--color-paper-dim:#e0d8c7;--color-seal:#b23a2e;--color-seal-soft:#d1594c;--color-brass:#c08a2e;--color-brass-soft:#e0b25a;--color-verdigris:#4f7d70}}
        </style>
    @endif

    @stack('head')
</head>
<body class="h-full min-h-screen font-sans antialiased">
    <a href="#main"
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:bg-brass focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-ink-950">
        Lewati ke konten utama
    </a>

    @include('vault.partials.topbar')

    <main id="main" class="mx-auto w-full max-w-[1400px] px-5 pb-24 sm:px-8">
        @yield('content')
    </main>

    @include('vault.partials.footer')

    @stack('scripts')
</body>
</html>
