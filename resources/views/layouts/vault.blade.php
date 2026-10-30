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
            /*! tailwindcss v4 fallback — build assets with `npm run build` */@import "tailwindcss";
@theme {--font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;--font-display: 'Fraunces', ui-serif, Georgia, serif;--font-mono: 'JetBrains Mono', ui-monospace, monospace;--color-ink-950: #0b0a08;--color-ink-900: #14120e;--color-ink-800: #221e18;--color-ink-700: #2e2920;--color-ink-600: #3d372c;--color-ink-500: #574f40;--color-ink-400: #7d7360;--color-ink-300: #a89c85;--color-ink-200: #cfc4ae;--color-ink-100: #e6ddca;--color-paper: #ede7da;--color-paper-dim: #e0d8c7;--color-seal: #b23a2e;--color-seal-soft: #d1594c;--color-brass: #c08a2e;--color-brass-soft: #e0b25a;--color-verdigris: #4f7d70;}@layer base {html {font-family: var(--font-sans);}}
        </style>
        @layer utilities {
            .fixed { position: fixed; }
            .absolute { position: absolute; }
            .inset-0 { top: 0; right: 0; bottom: 0; left: 0; }
            .z-\[9999\] { z-index: 9999; }
            .flex { display: flex; }
            .items-center { align-items: center; }
            .justify-center { justify-content: center; }
            .hidden { display: none; }
            .backdrop-blur-sm { backdrop-filter: blur(4px); }
            .bg-black\\/50 { background-color: rgba(0, 0, 0, 0.5); }
            .bg-white { background-color: white; }
            .rounded-xl { border-radius: 0.75rem; }
            .max-w-2xl { max-width: 42rem; }
            .w-full { width: 100%; }
            .mx-4 { margin-left: 1rem; margin-right: 1rem; }
            .overflow-hidden { overflow: hidden; }
            .border { border-width: 1px; }
            .border-gray-200 { border-color: #e5e7eb; }
            .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
            .bg-gray-50 { background-color: #f9fafb; }
            .border-b { border-bottom-width: 1px; }
            .border-gray-200 { border-color: #e5e7eb; }
            .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
            .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
            .text-lg { font-size: 1.125rem; }
            .font-semibold { font-weight: 600; }
            .text-gray-900 { color: #111827; }
            .text-gray-400 { color: #9ca3af; }
            .hover\\:text-gray-600:hover { color: #4b5563; }
            .p-1 { padding: 0.25rem; }
            .rounded-lg { border-radius: 0.5rem; }
            .grid { display: grid; }
            .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
            .md\\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .gap-6 { gap: 1.5rem; }
            .min-h-\\[220px\\] { min-height: 220px; }
            .text-sm { font-size: 0.875rem; }
            .space-y-3 > * + * { margin-top: 0.75rem; }
            .text-\\[10px\\] { font-size: 0.625rem; }
            .font-mono { font-family: var(--font-mono); }
            .uppercase { text-transform: uppercase; }
            .tracking-wider { letter-spacing: 0.05em; }
            .text-gray-400 { color: #9ca3af; }
            .font-medium { font-weight: 500; }
            .text-gray-700 { color: #374151; }
            .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .text-emerald-700 { color: #047857; }
            .text-emerald-600 { color: #059669; }
            .pt-2 { padding-top: 0.5rem; }
            .border-t { border-top-width: 1px; }
            .border-gray-100 { border-color: #f3f4f6; }
            .text-xs { font-size: 0.75rem; }
        }
    @endif

    @stack('head')
</head>
<body class="h-full w-full min-h-screen font-sans antialiased bg-paper">
    <main id="main" class="w-full min-h-screen">
        @yield('content')
    </main>

    {{-- Card Detail Modal --}}
    {{-- Card Detail Modal --}}<div id="card-modal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; background: rgba(0,0,0,0.5);" class="flex items-center justify-center hidden" onclick="closeCardModal()">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 overflow-y-auto border border-gray-200" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                    <h3 id="modal-company" class="font-semibold text-gray-900 text-lg">Company Name</h3>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" id="modal-edit-btn" onclick="enableModalEdit()" class="px-3 py-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg text-xs font-medium flex items-center gap-1.5 shadow-sm transition-colors">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Details
                    </button>
                    <button onclick="closeCardModal()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-200/50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div id="modal-image-container" class="flex flex-col items-center justify-center bg-gray-50 rounded-lg p-4 border border-gray-100 min-h-[220px]">
                <img id="modal-card-image" src="" alt="Business Card" class="max-h-64 object-contain rounded shadow">
                <span id="modal-no-image" class="text-xs text-gray-400 italic hidden">No card image attached</span>
            </div>
            <form id="modal-edit-form" action="" method="POST" class="space-y-3 text-sm">
                @csrf
                @method('PUT')
                <div>
                    <span class="text-[10px] font-mono text-gray-400 uppercase tracking-wider">Name</span>
                    <input type="text" name="name" id="modal-input-name" readonly class="w-full bg-transparent border-b border-gray-200 focus:border-emerald-600 outline-none font-medium text-gray-900 text-base py-0.5 cursor-default focus:cursor-text">
                </div>
                <div>
                    <span class="text-[10px] font-mono text-gray-400 uppercase tracking-wider">Title</span>
                    <input type="text" name="title" id="modal-input-title" readonly class="w-full bg-transparent border-b border-gray-200 focus:border-emerald-600 outline-none text-gray-700 py-0.5 cursor-default focus:cursor-text">
                </div>
                <div>
                    <span class="text-[10px] font-mono text-gray-400 uppercase tracking-wider">Email</span>
                    <input type="email" name="email" id="modal-input-email" readonly class="w-full bg-transparent border-b border-gray-200 focus:border-emerald-600 outline-none text-emerald-700 font-medium py-0.5 cursor-default focus:cursor-text">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <span class="text-[10px] font-mono text-gray-400 uppercase tracking-wider">Phone</span>
                        <input type="text" name="phone" id="modal-input-phone" readonly class="w-full bg-transparent border-b border-gray-200 focus:border-emerald-600 outline-none text-gray-700 py-0.5 cursor-default focus:cursor-text">
                    </div>
                    <div>
                        <span class="text-[10px] font-mono text-gray-400 uppercase tracking-wider">Office Phone</span>
                        <input type="text" name="office_phone" id="modal-input-office-phone" readonly class="w-full bg-transparent border-b border-gray-200 focus:border-emerald-600 outline-none text-gray-700 py-0.5 cursor-default focus:cursor-text">
                    </div>
                </div>
                <div>
                    <span class="text-[10px] font-mono text-gray-400 uppercase tracking-wider">Website</span>
                    <input type="text" name="website" id="modal-input-website" readonly class="w-full bg-transparent border-b border-gray-200 focus:border-emerald-600 outline-none text-emerald-600 py-0.5 cursor-default focus:cursor-text">
                </div>
                <div>
                    <span class="text-[10px] font-mono text-gray-400 uppercase tracking-wider">Address</span>
                    <input type="text" name="address" id="modal-input-address" readonly class="w-full bg-transparent border-b border-gray-200 focus:border-emerald-600 outline-none text-gray-700 text-xs py-0.5 cursor-default focus:cursor-text">
                </div>
                <div>
                    <span class="text-[10px] font-mono text-gray-400 uppercase tracking-wider">LinkedIn</span>
                    <input type="text" name="linkedin" id="modal-input-linkedin" readonly class="w-full bg-transparent border-b border-gray-200 focus:border-emerald-600 outline-none text-emerald-600 py-0.5 cursor-default focus:cursor-text">
                </div>
                
                <div class="pt-4 flex items-center justify-between">
                    <div id="modal-actions" class="flex items-center gap-2 hidden">
                        <button type="submit" id="modal-save-btn" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Save Changes
                        </button>
                        <button type="button" id="modal-cancel-btn" onclick="disableModalEdit()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition-colors">
                            Cancel
                        </button>
                    </div>
                    <div id="modal-date" class="text-[10px] text-gray-400 uppercase tracking-wider">-</div>
                </div>
            </form>
        </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
