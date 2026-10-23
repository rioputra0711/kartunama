@extends('layouts.vault')

@section('title', 'Katalog Kontak & Pemindai')

@section('content')
<div class="space-y-10">
    {{-- Hero Section / Scanner Hub --}}
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Left: Upload & Intake Panel (5 cols) --}}
        <div class="lg:col-span-5 vault-card p-6 sm:p-8 rounded-xl shadow-2xl relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-48 h-48 bg-brass/5 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-xl font-bold tracking-tight text-paper flex items-center gap-2">
                    <span>Scan Kartu Nama</span>
                </h2>
                <span class="tag bg-brass/10 border border-brass/30 text-brass-soft px-2 py-0.5 rounded">
                    AI Vision / OCR
                </span>
            </div>

            <p class="text-sm text-ink-300 mb-6 font-sans">
                Unggah foto kartu nama fisik untuk ekstraksi otomatis. Sistem mendeteksi teks, nama perusahaan, kontak, serta alamat secara presisi.
            </p>

            {{-- Dropzone Form --}}
            <form action="{{ route('cards.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" id="scan-form">
                @csrf
                <div class="dropzone border-2 border-dashed border-ink-600 rounded-lg p-8 text-center transition-all cursor-pointer bg-ink-950/40 hover:border-brass/60 relative group"
                     id="dropzone">
                    <input type="file" name="card_image" id="card_image" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" accept="image/jpeg,image/png,image/webp">
                    
                    <div class="flex flex-col items-center justify-center space-y-3 pointer-events-none">
                        <div class="w-12 h-12 rounded-full bg-ink-900 border border-ink-700 flex items-center justify-center text-brass group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-paper" id="dropzone-text">Seret foto kartu di sini, atau <span class="text-brass underline underline-offset-4">pilih berkas</span></p>
                            <p class="tag text-ink-400 mt-1" id="dropzone-subtext">JPG, PNG, WebP • Maksimal 8 MB</p>
                        </div>
                    </div>
                </div>

                {{-- Action Options --}}
                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center gap-2 font-mono text-xs text-ink-400">
                        <span class="inline-block w-2 h-2 rounded-full bg-verdigris"></span>
                        <span>Clipboard (Ctrl+V) Aktif</span>
                    </div>
                    <button type="submit" class="bg-brass hover:bg-brass-soft text-ink-950 font-semibold px-5 py-2.5 rounded-sm text-sm transition-all shadow-lg shadow-brass/10 cursor-pointer flex items-center gap-2">
                        <span>Proses Ekstraksi</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>
        </div>

        {{-- Right: Quick Stats & Vault Health (7 cols) --}}
        <div class="lg:col-span-7 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="vault-card p-5 rounded-xl">
                    <span class="tag text-ink-400">Total Kontak</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="font-display text-3xl font-bold text-paper">0</span>
                        <span class="font-mono text-xs text-verdigris">+0 hari ini</span>
                    </div>
                </div>
                <div class="vault-card p-5 rounded-xl">
                    <span class="tag text-ink-400">Perusahaan</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="font-display text-3xl font-bold text-paper">0</span>
                        <span class="font-mono text-xs text-ink-400">entitas</span>
                    </div>
                </div>
                <div class="vault-card p-5 rounded-xl">
                    <span class="tag text-ink-400">Rata-rata Akurasi</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="font-display text-3xl font-bold text-brass-soft">99.4%</span>
                        <span class="font-mono text-xs text-ink-400">Vision</span>
                    </div>
                </div>
            </div>

            {{-- Notice Banner --}}
            <div class="vault-card p-6 rounded-xl bg-gradient-to-r from-ink-900 to-ink-850 border-l-4 border-l-brass">
                <h3 class="font-display text-lg font-semibold text-paper mb-1">Panduan Pengarsipan Cepat</h3>
                <p class="text-sm text-ink-300 font-sans leading-relaxed">
                    Setiap kartu yang dipindai akan diverifikasi otomatis oleh mesin sebelum disimpan ke database MySQL. Anda dapat menyunting bidang kontak kapan saja setelah proses ekstraksi selesai.
                </p>
            </div>
        </div>
    </section>

    {{-- Contact Gallery / Archive Section --}}
    <section class="space-y-6">
        {{-- Search & Filtering Header --}}
        <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center justify-between rule-top pt-8">
            <div class="relative flex-1 max-w-xl">
                <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-ink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Cari kontak berdasarkan nama, jabatan, atau perusahaan..." class="field-input pl-11 rounded-lg">
            </div>

            <div class="flex items-center gap-3">
                <select class="field-input rounded-lg cursor-pointer text-xs font-mono">
                    <option>Urutkan: Terbaru</option>
                    <option>Nama (A-Z)</option>
                    <option>Perusahaan</option>
                    <option>Confidence Score Tertinggi</option>
                </select>
            </div>
        </div>

        {{-- Contact Cards Grid / Empty State --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Empty State Presentation --}}
            <div class="vault-card p-12 rounded-xl text-center col-span-full py-20 flex flex-col items-center justify-center">
                <div class="w-14 h-14 rounded-full bg-ink-900 border border-ink-700 flex items-center justify-center text-ink-400 mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h4 class="font-display text-lg font-semibold text-paper mb-1">Belum ada kartu tersimpan</h4>
                <p class="text-sm text-ink-400 max-w-md mx-auto font-sans">
                    Arsip kontak masih kosong. Unggah foto kartu nama pertama Anda melalui panel di atas untuk mengisi database Vault.
                </p>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fileInput = document.getElementById('card_image');
        const dropzoneText = document.getElementById('dropzone-text');
        const dropzoneSubtext = document.getElementById('dropzone-subtext');

        if (fileInput) {
            fileInput.addEventListener('change', (e) => {
                if (e.target.files && e.target.files[0]) {
                    const fileName = e.target.files[0].name;
                    dropzoneText.innerHTML = `<span class="text-verdigris font-semibold">Berkas terpilih:</span> ${fileName}`;
                    dropzoneSubtext.textContent = 'Klik "Proses Ekstraksi" untuk mulai menyimpan';
                }
            });
        }
    });
</script>
@endpush
@endsection
