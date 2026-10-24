@extends('layouts.vault')

@section('title', 'Vault — Every connection. In one place.')

@section('content')
<div class="min-h-screen bg-[#fafaf9] text-gray-900 font-sans">
    
    {{-- Navbar Section --}}
    <nav class="flex items-center justify-between px-8 py-4 border-b border-gray-200 bg-white">
        <div class="flex items-center gap-2 font-medium text-lg tracking-tight">
            kartu nama <span class="text-xs font-bold text-gray-500 tracking-widest mt-1">VAULT <span class="text-emerald-700">■</span></span>
        </div>
        
        {{-- Search bar sebelumnya ada di sini, sekarang dipindah ke bawah --}}

        <div class="flex items-center gap-4 text-sm font-medium text-gray-600">
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Personal vault
            </div>
            <div class="w-8 h-8 bg-emerald-50 rounded-full border border-emerald-200 flex items-center justify-center text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>
    </nav>

    {{-- Header & Stats --}}
    <header class="max-w-7xl mx-auto px-8 pt-12 pb-10 flex flex-col md:flex-row justify-between items-end">
        <div>
            <p class="text-[11px] font-bold tracking-widest text-emerald-700 uppercase mb-3">YOUR NETWORK, ORGANIZED.</p>
            <h1 class="text-5xl font-normal tracking-tight text-gray-900 mb-4">Every connection. In one place.</h1>
            <p class="text-gray-500">Scan a business card. Verify the details. Keep the connection.</p>
        </div>
        <div class="flex items-center gap-12 mt-8 md:mt-0">
            <div>
                <p class="text-4xl font-light text-gray-900">{{ $cards->total() ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">Saved contacts</p>
            </div>
            <div class="border-l border-gray-200 pl-12">
                <p class="text-4xl font-light text-emerald-700">@isset($previewData) 01 @else 00 @endisset</p>
                <p class="text-xs text-gray-500 mt-1">Ready to review</p>
            </div>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="max-w-7xl mx-auto px-8 pb-20 space-y-12">
        
        {{-- Default Upload Zone --}}
        @if(!isset($previewData))
        <div id="default-upload-zone" class="bg-[#12221b] text-white rounded-lg p-10 flex flex-col md:flex-row items-center justify-between shadow-lg relative overflow-hidden">
            <div class="flex items-center gap-6 relative z-10">
                <div class="w-16 h-16 border border-white/20 rounded border-dashed flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-medium mb-1">Drop cards. Make connections. <span class="text-[9px] font-mono tracking-widest text-white/50 ml-2 uppercase">Batch Upload</span></h2>
                    <p class="text-sm text-white/70 mb-2">Drag & drop business cards here, or choose how to add them.</p>
                    <p class="text-[10px] font-mono text-white/40 tracking-wider">JPG, PNG, WEBP / MAX 8 MB PER FILE</p>
                </div>
            </div>
            
            <form action="{{ route('cards.preview') }}" method="POST" enctype="multipart/form-data" id="upload-form" class="flex flex-wrap items-center gap-3 relative z-10 mt-6 md:mt-0">
                @csrf
                <button type="button" class="px-4 py-2.5 bg-transparent border border-white/20 hover:bg-white/10 rounded flex items-center gap-2 text-sm transition-colors cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                    Camera
                </button>
                <div class="relative group">
                    <input type="file" name="card_image" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="submitUploadForm(this)">
                    <button type="button" class="px-5 py-2.5 bg-[#007b55] hover:bg-[#006644] text-white rounded flex items-center gap-2 text-sm transition-colors cursor-pointer pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Upload files
                    </button>
                </div>
                <button type="button" onclick="enableManualInput()" class="px-4 py-2.5 bg-transparent border border-white/20 hover:bg-white/10 rounded flex items-center gap-2 text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Manual Input
                </button>
            </form>
            <p class="absolute bottom-4 right-10 text-[10px] text-white/40">Have a card copied? Paste with Ctrl + V</p>
            
            {{-- Loading overlay --}}
            <div id="loading-overlay" class="absolute inset-0 bg-[#12221b] flex items-center justify-center hidden z-50">
                <div class="flex items-center gap-3 text-white">
                    <svg class="animate-spin h-5 w-5 text-[#007b55]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span class="text-sm">Processing with Gemini AI...</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Split-View Verification / Manual Form Panel --}}
        <div id="verification-panel" class="bg-white border border-gray-200 rounded-lg shadow-sm {{ isset($previewData) ? 'block' : 'hidden' }}">
            <div class="px-8 py-5 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4 bg-gray-50/50 rounded-t-lg">
                <div class="flex items-center gap-2">
                    <span class="text-emerald-700 font-medium text-sm">01 / Verify contact details</span>
                    <span class="text-gray-400 text-sm">Ready for your review</span>
                </div>
                <div id="match-badge" class="flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded text-xs font-semibold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    98% Match
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12">
                {{-- Left Side: Image Preview --}}
                <div id="image-side" class="lg:col-span-4 p-8 border-b lg:border-b-0 lg:border-r border-gray-200 bg-gray-50 flex flex-col">
                    <div class="flex items-center justify-between text-[10px] font-mono text-gray-500 uppercase tracking-widest mb-6">
                        <span>Original Card</span>
                        <span>400 PX ↗</span>
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-center mb-6">
                        <img id="card-preview-img" src="{{ isset($previewData) ? asset('storage/' . $previewData['file_path']) : '' }}" alt="Card" class="w-full shadow-md border border-gray-200">
                    </div>
                    
                    <div class="mt-auto">
                        <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded text-sm mb-6">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <div class="truncate">
                                    <p id="card-filename" class="font-medium text-gray-900 truncate">{{ $previewData['original_filename'] ?? 'manual_entry' }}</p>
                                    <p id="card-filesize" class="text-xs text-gray-500">{{ $previewData['filesize'] ?? '0 MB' }} Uploaded just now</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        
                        <div class="flex items-center justify-between text-sm">
                            <button type="button" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download original
                            </button>
                            <button type="button" onclick="cancelManualInput()" class="flex items-center gap-2 text-gray-600 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Delete image
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-4">Your original image stays with this contact unless you choose to delete it.</p>
                    </div>
                </div>

                {{-- Right Side: Editable Form --}}
                <div class="lg:col-span-8 p-8 flex flex-col">
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Extracted from your card. Click any field to make a correction.
                    </div>

                    <form action="{{ route('cards.store') }}" method="POST" id="verify-form" class="flex-1 flex flex-col">
                        @csrf
                        <input type="hidden" name="file_path" id="input-file_path" value="{{ $previewData['file_path'] ?? '' }}">
                        <input type="hidden" name="original_filename" id="input-original_filename" value="{{ $previewData['original_filename'] ?? '' }}">
                        <input type="hidden" name="raw_ocr_json" id="input-raw_json" value="{{ $previewData['raw_ocr_json'] ?? '' }}">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1.5">Name</label>
                                <input type="text" name="name" value="{{ $previewData['name'] ?? '' }}" class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-gray-900 text-sm focus:outline-none focus:border-emerald-600" required>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1.5">Company</label>
                                <input type="text" name="company" value="{{ $previewData['company'] ?? '' }}" class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-gray-900 text-sm focus:outline-none focus:border-emerald-600" required>
                            </div>
                            
                            <div>
                                <label class="block text-xs text-gray-600 mb-1.5">Title</label>
                                <input type="text" name="title" value="{{ $previewData['title'] ?? '' }}" class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-gray-900 text-sm focus:outline-none focus:border-emerald-600">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1.5">Phone</label>
                                <input type="text" name="phone" value="{{ $previewData['phone'] ?? '' }}" class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-gray-900 text-sm focus:outline-none focus:border-emerald-600">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-600 mb-1.5">Email</label>
                                <input type="email" name="email" value="{{ $previewData['email'] ?? '' }}" class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-gray-900 text-sm focus:outline-none focus:border-emerald-600">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1.5">Office Phone · optional</label>
                                <input type="text" name="office_phone" value="{{ $previewData['office_phone'] ?? '' }}" placeholder="Not detected" class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-gray-900 text-sm focus:outline-none focus:border-emerald-600 placeholder-gray-400">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-600 mb-1.5">Website</label>
                                <input type="text" name="website" value="{{ $previewData['website'] ?? '' }}" class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-gray-900 text-sm focus:outline-none focus:border-emerald-600">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1.5">LinkedIn · optional</label>
                                <input type="text" name="linkedin" value="{{ $previewData['linkedin'] ?? '' }}" placeholder="Not detected" class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-gray-900 text-sm focus:outline-none focus:border-emerald-600 placeholder-gray-400">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs text-gray-600 mb-1.5">Address</label>
                                <input type="text" name="address" value="{{ $previewData['address'] ?? '' }}" placeholder="Not detected" class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-gray-900 text-sm focus:outline-none focus:border-emerald-600 placeholder-gray-400">
                            </div>
                        </div>

                        <div class="mt-auto pt-8 flex items-center justify-between">
                            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-mono">
                                Not on the card? We leave it empty.<br>
                                DEMO CONTACT · CONCEPTUAL DATA
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('cards.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded text-sm font-medium transition-colors">
                                    Discard
                                </a>
                                <button type="submit" class="px-5 py-2.5 bg-[#007b55] hover:bg-[#006644] text-white rounded flex items-center gap-2 text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Save Contact
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Contact Database Section --}}
        <div class="pt-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3 shrink-0">
                    <h2 class="text-3xl font-normal text-gray-900">Your connections</h2>
                    <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 rounded text-xs text-gray-600">{{ $cards->total() }}</span>
                </div>

                {{-- SEARCH BAR (Dipindahkan ke area sini) --}}
                <div class="flex-1 w-full max-w-xl mx-0 lg:mx-6">
                    <div class="flex items-center bg-white border border-gray-300 rounded-md px-3 py-2.5 text-sm text-gray-400 group focus-within:ring-1 focus-within:ring-emerald-500 focus-within:border-emerald-500 transition-all shadow-sm relative">
                        <svg class="w-5 h-5 mr-2 text-gray-400 group-focus-within:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" id="search-input" value="{{ request('q') }}" placeholder="Search contacts by name or company..." class="bg-transparent w-full outline-none text-gray-700 placeholder-gray-400" autocomplete="off">
                        @if(request()->has('q'))
                            <button type="button" onclick="clearSearch()" class="text-gray-400 hover:text-gray-600 ml-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        @endif
                        <span class="flex items-center gap-1 border border-gray-200 rounded px-1.5 py-0.5 text-[10px] text-gray-400 font-mono ml-2"><kbd>⌘</kbd> K</span>
                    </div>
                </div>
                {{-- END SEARCH BAR --}}

                <div class="flex items-center gap-3 shrink-0">
                    <button class="px-3 py-2 bg-white border border-gray-200 rounded flex items-center gap-2 text-sm text-gray-700 hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        All contacts
                    </button>
                    <button class="px-3 py-2 bg-white border border-gray-200 rounded flex items-center gap-2 text-sm text-gray-700 hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                        Recently added
                    </button>
                    <div class="flex bg-white border border-gray-200 rounded overflow-hidden">
                        <button class="p-2 bg-gray-900 text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg></button>
                        <button class="p-2 text-gray-400 hover:text-gray-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                    </div>
                </div>
            </div>
            
            <p class="text-sm text-gray-500 hidden sm:block mb-8">A little less searching. A lot more connecting.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($cards as $index => $card)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden flex flex-col group cursor-pointer hover:border-emerald-600 transition-all shadow-sm hover:shadow-md" onclick="openCardModal({{ json_encode($card) }})">
                        {{-- Card Header Styling --}}
                        <div class="h-40 p-6 flex flex-col justify-end text-white relative {{ ['bg-[#12221b]', 'bg-[#1b2533]', 'bg-[#f0ece1] text-gray-900', 'bg-[#000000]', 'bg-[#e3dcd1] text-gray-900', 'bg-[#f4f7f6] text-gray-900'][$index % 6] }}">
                            @if($card->file_path)
                                <img src="{{ asset('storage/' . $card->file_path) }}" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay">
                            @endif
                            <div class="relative z-10">
                                <h3 class="font-medium text-lg leading-tight">{{ $card->company }}</h3>
                                <p class="text-xs opacity-70 mt-1">{{ $card->email }}</p>
                            </div>
                        </div>
                        
                        {{-- Card Body --}}
                        <div class="p-5 flex-1 flex flex-col bg-white">
                            <div class="flex items-start justify-between mb-1">
                                <h4 class="text-lg font-medium text-gray-900">{{ $card->name }}</h4>
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] rounded uppercase tracking-wider">Saved</span>
                            </div>
                            <p class="text-sm text-gray-500">{{ $card->title ?? 'Professional' }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $card->company }}</p>
                            
                            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-sm" onclick="event.stopPropagation()">
                                <button class="flex items-center gap-2 text-[#007b55] hover:text-[#00553b] font-medium transition-colors" onclick="navigator.clipboard.writeText('{{ $card->email }}'); alert('Email dicopy!')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    Copy Email
                                </button>
                                <span class="text-gray-400 text-xs truncate max-w-[120px]">{{ $card->website ?? strtolower(str_replace(' ', '', $card->company)) . '.example' }}</span>
                                <form action="{{ route('cards.destroy', $card->id) }}" method="POST" onsubmit="return confirm('Delete connection?')" onclick="event.stopPropagation()">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex items-center gap-1.5 text-gray-500 hover:text-gray-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        Edit
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-200 rounded-lg text-gray-500">
                        No contacts found. Upload a business card to get started.
                    </div>
                @endforelse
            </div>

            @if($cards->hasPages())
                <div class="mt-8 flex items-center justify-between border-t border-gray-200 pt-6">
                    <p class="text-sm text-gray-500">Showing {{ $cards->firstItem() ?? 0 }}–{{ $cards->lastItem() ?? 0 }} of {{ $cards->total() }} contacts</p>
                    <div class="flex items-center gap-2">
                        @if($cards->onFirstPage())
                            <button class="px-4 py-2 border border-gray-200 rounded text-gray-300 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                        @else
                            <a href="{{ $cards->previousPageUrl() }}" class="px-4 py-2 border border-gray-200 rounded text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </a>
                        @endif

                        @if($cards->hasMorePages())
                            <a href="{{ $cards->nextPageUrl() }}" class="px-4 py-2 border border-gray-200 rounded text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        @else
                            <button class="px-4 py-2 border border-gray-200 rounded text-gray-300 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </main>

    <footer class="border-t border-gray-200 bg-white py-6">
        <div class="max-w-7xl mx-auto px-8 flex items-center justify-between text-[10px] text-gray-500 font-mono tracking-widest uppercase">
            <span>KARTU NAMA VAULT / YOUR PERSONAL CONTACT DATABASE</span>
            <span class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Private by default. Yours to keep.
            </span>
        </div>
    </footer>
</div>

@push('scripts')
<script>
    function submitUploadForm(input) {
        if (input.files && input.files[0]) {
            document.getElementById('loading-overlay').classList.remove('hidden');
            document.getElementById('upload-form').submit();
        }
    }

    function enableManualInput() {
        document.getElementById('default-upload-zone').classList.add('hidden');
        document.getElementById('verification-panel').classList.remove('hidden');
        document.getElementById('image-side').classList.add('hidden');
        document.getElementById('match-badge').innerHTML = 'Manual Entry';
        
        // Reset inputs
        const form = document.getElementById('verify-form');
        form.reset();
        document.getElementById('input-file_path').value = '';
        document.getElementById('input-original_filename').value = '';
        document.getElementById('input-raw_json').value = '';
    }

    function cancelManualInput() {
        if(!document.getElementById('input-file_path').value) {
            document.getElementById('default-upload-zone').classList.remove('hidden');
            document.getElementById('verification-panel').classList.add('hidden');
            document.getElementById('image-side').classList.remove('hidden');
        } else {
            window.location.href = "{{ route('cards.index') }}";
        }
    }

    function openCardModal(card) {
        console.log('openCardModal called with:', card);
        const modal = document.getElementById('card-modal');
        console.log('card-modal element:', modal);
        if (!modal) { console.error('card-modal not found in DOM'); return; }
        const modalCompany = document.getElementById('modal-company');
        console.log('modal-company element:', modalCompany);
        if (!modalCompany) { console.error('modal-company not found in DOM'); return; }
        modalCompany.innerText = card.company || 'N/A';
        const modalName = document.getElementById('modal-name');
        if (!modalName) { console.error('modal-name not found in DOM'); return; }
        modalName.innerText = card.name || 'N/A';
        document.getElementById('modal-title').innerText = card.title || 'N/A';
        document.getElementById('modal-email').innerText = card.email || 'N/A';
        document.getElementById('modal-phone').innerText = card.phone || 'N/A';
        document.getElementById('modal-office-phone').innerText = card.office_phone || '-';
        document.getElementById('modal-website').innerText = card.website || '-';
        document.getElementById('modal-address').innerText = card.address || '-';
        document.getElementById('modal-linkedin').innerText = card.linkedin || '-';
        
        let dateStr = card.created_at ? new Date(card.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : '-';
        document.getElementById('modal-date').innerText = 'Saved on: ' + dateStr;

        const imgEl = document.getElementById('modal-card-image');
        const noImgEl = document.getElementById('modal-no-image');
        if (card.file_path) {
            imgEl.src = "{{ asset('storage') }}/" + card.file_path;
            imgEl.classList.remove('hidden');
            noImgEl.classList.add('hidden');
        } else {
            imgEl.classList.add('hidden');
            noImgEl.classList.remove('hidden');
        }

        modal.style.display = 'flex';
    }

    function closeCardModal() {
        const modal = document.getElementById('card-modal');
        modal.style.display = 'none';
    }

    function clearSearch() {
        window.location.href = "{{ route('cards.index') }}";
    }

    // Live search with AJAX - filter cards without reload
    let searchTimeout;
    const searchInput = document.getElementById('search-input');
    const cardsGrid = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
    const paginationContainer = document.querySelector('div.flex.items-center.justify-between.border-t.border-gray-200.pt-6');
    const paginationText = document.querySelector('p.text-sm.text-gray-500.mt-8');
    
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length === 0) {
            window.location.href = "{{ route('cards.index') }}";
            return;
        }
        
        searchTimeout = setTimeout(function() {
            performLiveSearch(query);
        }, 300);
    });
    
    function performLiveSearch(query) {
        fetch("{{ route('cards.index') }}?q=" + encodeURIComponent(query) + "&page=1")
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Extract cards and pagination
                const newCards = doc.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
                const newPagination = doc.querySelector('div.flex.items-center.justify-between.border-t.border-gray-200.pt-6');
                
                if (newCards) {
                    cardsGrid.innerHTML = newCards.innerHTML;
                }
                if (newPagination) {
                    paginationContainer.innerHTML = newPagination.innerHTML;
                }
                
                // Update pagination text
                if (doc.querySelector('p.text-sm.text-gray-500.mt-8')) {
                    paginationText.innerHTML = doc.querySelector('p.text-sm.text-gray-500.mt-8').innerHTML;
                }
            })
            .catch(err => console.error('Search error:', err));
    }
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target !== searchInput) {
            // No suggestions dropdown needed for live filter
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCardModal();
        }
    });
</script>
@endpush
@endsection