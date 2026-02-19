@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-2" x-data="{ openModal: false, openManage: false }">

    {{-- 1. NOTIFIKASI SLIM - POSISI TENGAH ATAS --}}
    <div 
        x-data="{ 
            show: false, 
            message: '', 
            type: 'success',
            init() {
                @if(session('success'))
                    this.showNotification('{{ session('success') }}', 'success');
                @endif
                @if(session('error'))
                    this.showNotification('{{ session('error') }}', 'error');
                @endif
            },
            showNotification(msg, type) {
                this.message = msg;
                this.type = type;
                this.show = true;
                setTimeout(() => { this.show = false }, 3000);
            }
        }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] pointer-events-none"
        x-cloak
    >
        <div :class="type === 'success' ? 'bg-white border-blue-600' : 'bg-white border-red-600'" 
             class="flex items-center gap-3 px-6 py-3 bg-white border-l-4 rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.12)] pointer-events-auto min-w-[320px]">
            
            {{-- Icon --}}
            <div :class="type === 'success' ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600'" class="p-1.5 rounded-lg">
                <template x-if="type === 'success'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </template>
                <template x-if="type === 'error'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </template>
            </div>

            {{-- Pesan --}}
            <div class="flex-1 text-center px-2">
                <p class="text-[11px] font-bold text-gray-700 tracking-tight leading-tight uppercase" x-text="message"></p>
            </div>

            {{-- Close --}}
            <button @click="show = false" class="text-gray-300 hover:text-gray-500 transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>

    {{-- 2. HEADER SECTION --}}
    <div class="flex justify-between items-center mb-6 border-b-2 border-yellow-400 pb-3">
        <div>
            <div class="flex items-center gap-2 mb-0.5">
                <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                <h2 class="text-xl font-black text-blue-900 tracking-tight uppercase italic leading-none">Monitoring Hibah</h2>
            </div>
            <p class="text-gray-400 font-bold text-[9px] ml-4 tracking-[0.2em] uppercase">Admin Control Panel</p>
        </div>
        <div class="flex gap-2">
            <button class="bg-white border border-blue-100 text-blue-600 px-3 py-1.5 rounded-xl shadow-sm hover:bg-blue-50 transition text-[9px] font-black uppercase tracking-widest">
                Export
            </button>
            <button @click="openModal = true" class="bg-blue-600 text-white px-4 py-1.5 rounded-xl shadow-md shadow-blue-100 hover:bg-blue-700 transition text-[9px] font-black uppercase tracking-widest border-b-2 border-blue-800 active:border-b-0 active:translate-y-0.5">
                + Tambah Paket
            </button>
        </div>
    </div>

    {{-- 3. STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div @click="openManage = true" class="bg-blue-600 p-5 rounded-3xl shadow-lg shadow-blue-100 cursor-pointer hover:scale-[1.02] transition-all border-b-4 border-blue-800 flex items-center gap-4">
            <div class="text-white">
                <p class="text-[8px] font-black opacity-70 uppercase tracking-widest mb-0.5">Total Paket</p>
                <div class="flex items-baseline gap-1">
                    <h4 class="text-3xl font-black">{{ $pakets->count() }}</h4>
                    <span class="text-yellow-300 font-bold text-[9px] uppercase">Unit</span>
                </div>
            </div>
        </div>

        <div class="bg-yellow-400 p-5 rounded-3xl shadow-lg shadow-yellow-50 border-b-4 border-yellow-500 flex flex-col justify-center">
            <p class="text-[8px] font-black text-yellow-900 opacity-70 uppercase tracking-widest mb-0.5">Struktur Alur</p>
            <h4 class="text-xl font-black text-blue-900 tracking-tighter uppercase italic leading-none">11 TAHAPAN</h4>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-sm border border-blue-50 flex flex-col justify-center">
            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Status Sistem</p>
            <div class="flex items-center gap-2">
                <h4 class="text-xl font-black text-blue-900 tracking-tighter uppercase italic leading-none">Online</h4>
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
            </div>
        </div>
    </div>

    {{-- 4. GRID PAKET --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
        @forelse($pakets as $paket)
            <div class="bg-white rounded-3xl shadow-sm border border-blue-50 overflow-hidden hover:shadow-xl hover:border-yellow-400 transition-all duration-300 group">
                <div class="p-5">
                    <div class="flex justify-between items-start mb-3">
                        <div class="p-2.5 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-yellow-400 group-hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <span class="text-[8px] font-black text-gray-200 uppercase tracking-tighter italic leading-none">ID: #{{ $paket->id }}</span>
                    </div>
                    <h3 class="text-sm font-black text-blue-900 mb-4 leading-snug min-h-[2.5rem] uppercase italic line-clamp-2">{{ $paket->nama_paket }}</h3>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                        <div>
                            <p class="text-[7px] font-black text-gray-300 uppercase tracking-widest mb-0.5">Submisi</p>
                            <p class="text-xs font-black text-blue-900">{{ $paket->submissions_count ?? 0 }} <span class="text-[8px] text-gray-400 font-bold uppercase ml-0.5">Berkas</span></p>
                        </div>
                        <a href="{{ route('admin.paket.users', $paket->id) }}" class="bg-blue-600 text-white px-4 py-1.5 rounded-lg text-[8px] font-black uppercase tracking-widest shadow-md shadow-blue-50 hover:bg-yellow-400 hover:text-blue-900 transition-all active:scale-95 italic">Monitor</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white py-12 rounded-3xl border-2 border-dashed border-blue-50 text-center">
                <p class="text-blue-200 font-black uppercase tracking-widest text-[10px]">Data Belum Tersedia</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL KELOLA DATABASE --}}
    <div x-show="openManage" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-blue-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg max-h-[80vh] overflow-hidden flex flex-col border-t-8 border-yellow-400" @click.away="openManage = false">
            <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-blue-50/20">
                <h3 class="text-lg font-black text-blue-900 uppercase italic leading-none">Manajemen Da
                    tabase</h3>
                <button @click="openManage = false" class="text-gray-300 hover:text-red-500 font-black text-xl leading-none">&times;</button>
            </div>
            <div class="p-5 overflow-y-auto space-y-2 custom-scrollbar">
                @foreach($pakets as $p)
                <div x-data="{ editing: false, newName: '{{ $p->nama_paket }}' }" class="flex items-center justify-between p-4 bg-white rounded-2xl border border-blue-50 hover:border-yellow-200 transition-all">
                    <div class="flex-1 mr-3">
                        <template x-if="!editing">
                            <span class="font-black text-blue-900 text-[11px] uppercase italic leading-tight block">{{ $p->nama_paket }}</span>
                        </template>
                        <template x-if="editing">
                            <form action="{{ route('admin.paket.update', $p->id) }}" method="POST" class="flex gap-2 w-full">
                                @csrf @method('PUT')
                                <input type="text" name="nama_paket" x-model="newName" class="flex-1 p-2 bg-blue-50 border-none rounded-xl text-[10px] font-black text-blue-900 focus:ring-2 focus:ring-yellow-300 outline-none uppercase italic">
                                <button type="submit" class="bg-blue-600 text-white px-3 rounded-xl text-[8px] font-black uppercase shadow-lg">Update</button>
                            </form>
                        </template>
                    </div>
                    <div class="flex gap-1" x-show="!editing">
                        <button @click="editing = true" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 012 2h11a2 2 0 012-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <form action="{{ route('admin.paket.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus paket ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH PAKET --}}
    <div x-show="openModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-blue-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white p-7 rounded-[2rem] shadow-2xl w-full max-w-sm border-b-4 border-blue-600" @click.away="openModal = false">
            <h3 class="text-xl font-black text-blue-900 mb-1 uppercase tracking-tighter italic">Paket Baru</h3>
            <p class="text-gray-400 mb-6 text-[8px] font-bold uppercase tracking-widest">Sistem otomatis membuat 11 tahapan</p>
            
            <form action="{{ route('admin.paket.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Paket</label>
                    <input type="text" name="nama_paket" class="w-full p-4 bg-blue-50 border-none rounded-2xl focus:ring-2 focus:ring-yellow-400 outline-none transition font-black text-blue-900 text-xs italic uppercase" placeholder="Input Nama..." required>
                </div>
                <div class="flex flex-col gap-2">
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-md border-b-2 border-blue-800 active:border-b-0 active:translate-y-0.5">Simpan</button>
                    <button type="button" @click="openModal = false" class="w-full py-1 text-gray-400 font-black text-[8px] uppercase tracking-widest hover:text-gray-600">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body { background-color: #fcfdfe; }
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endsection