@forelse($cards as $index => $card)
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden flex flex-col group cursor-pointer hover:border-[#007b55]/50 transition-all shadow-sm hover:shadow-md" onclick="openCardModal({{ json_encode($card) }})">
        
        {{-- Card Header --}}
        <div class="h-40 p-6 flex flex-col justify-end text-white relative {{ ['bg-[#12221b]', 'bg-[#1b2533]', 'bg-[#f0ece1] text-gray-900', 'bg-[#000000]', 'bg-[#e3dcd1] text-gray-900', 'bg-[#f4f7f6] text-gray-900'][$index % 6] }}">
            @if($card->file_path)
                <img src="{{ asset('storage/' . $card->file_path) }}" class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay">
            @endif
            <div class="relative z-10">
                <h3 class="font-medium text-xl leading-tight mb-1">{{ $card->company }}</h3>
                @if($card->website)
                    <p class="text-xs opacity-90 flex items-center gap-1.5 truncate">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        {{ $card->website }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-5 flex-1 flex flex-col bg-white">
            <div class="flex items-start justify-between mb-1">
                <h4 class="text-lg font-medium text-gray-900 pr-2 truncate" title="{{ $card->name }}">{{ $card->name }}</h4>
                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[9px] rounded uppercase tracking-wider shrink-0 border border-gray-200">Saved</span>
            </div>
            
            {{-- Jabatan (Title) --}}
            @if($card->title)
                <p class="text-sm text-gray-600 font-medium mt-0.5 truncate" title="{{ $card->title }}">{{ $card->title }}</p>
            @endif

            {{-- Detail Info (Phone, Email, Address) --}}
            <div class="mt-4 space-y-2">
                @if($card->phone)
                <p class="text-xs text-gray-500 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    {{ $card->phone }}
                </p>
                @endif

                @if($card->email)
                <p class="text-xs text-gray-500 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span class="truncate" title="{{ $card->email }}">{{ $card->email }}</span>
                </p>
                @endif

                @if($card->address)
                <p class="text-xs text-gray-500 flex items-start gap-2">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="line-clamp-2" title="{{ $card->address }}">{{ $card->address }}</span>
                </p>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between text-sm" onclick="event.stopPropagation()">
                @if($card->email)
                <button class="flex items-center gap-1.5 text-[#007b55] hover:text-[#00553b] font-medium transition-colors text-xs cursor-pointer" onclick="navigator.clipboard.writeText('{{ $card->email }}'); alert('Email dicopy!')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    Copy Email
                </button>
                @else
                <span class="text-[10px] text-gray-400 uppercase tracking-widest">No Email</span>
                @endif
                
                <form action="{{ route('cards.destroy', $card->id) }}" method="POST" onsubmit="return confirm('Hapus kartu kontak ini dari Vault?')" onclick="event.stopPropagation()">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex items-center gap-1 text-gray-400 hover:text-red-600 transition-colors cursor-pointer p-1" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
@empty
    <div class="col-span-full py-16 text-center border-2 border-dashed border-gray-200 rounded-lg text-gray-500 bg-white">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        <p class="text-gray-900 font-medium mb-1">No contacts found</p>
        <p class="text-sm">Upload your first business card to get started.</p>
    </div>
@endforelse