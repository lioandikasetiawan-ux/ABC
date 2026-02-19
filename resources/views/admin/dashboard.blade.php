@extends('layouts.app')

@section('content')
{{-- Kontainer Utama: Dibuat tanpa scroll (overflow-hidden) jika diinginkan full screen --}}
<div class="max-w-6xl mx-auto px-4" x-data="{ openModal: false, openManage: false }">
    {{-- Header Section --}}
    <div class="flex justify-between items-end mb-10 border-b-4 border-yellow-400 pb-6">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                <h2 class="text-3xl font-black text-blue-900 tracking-tight uppercase italic">Monitoring Hibah</h2>
            </div>
            <p class="text-gray-500 font-bold text-xs ml-5 tracking-widest uppercase">Admin Control Panel • BBWS System</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-white border-2 border-blue-100 text-blue-600 px-5 py-2.5 rounded-2xl shadow-sm hover:bg-blue-50 transition text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                Export
            </button>
            <button @click="openModal = true" class="bg-blue-600 text-white px-6 py-2.5 rounded-2xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition text-[10px] font-black uppercase tracking-widest border-b-4 border-blue-800 active:border-b-0 active:translate-y-1">
                + Tambah Paket
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div @click="openManage = true" class="bg-blue-600 p-8 rounded-[2.5rem] shadow-2xl shadow-blue-200 cursor-pointer hover:scale-[1.03] transition-all group relative border-b-8 border-blue-800">
            <div class="relative z-10 text-white">
                <p class="text-[10px] font-black opacity-70 uppercase tracking-[0.3em] mb-1">Total Paket</p>
                <div class="flex items-baseline gap-2">
                    <h4 class="text-5xl font-black">{{ $pakets->count() }}</h4>
                    <span class="text-yellow-300 font-bold text-xs uppercase">Unit</span>
                </div>
            </div>
        </div>

        <div class="bg-yellow-400 p-8 rounded-[2.5rem] shadow-2xl shadow-yellow-100 border-b-8 border-yellow-500 flex flex-col justify-center">
            <p class="text-[10px] font-black text-yellow-900 opacity-70 uppercase tracking-[0.3em] mb-1">Struktur Alur</p>
            <h4 class="text-4xl font-black text-blue-900 tracking-tighter uppercase italic">11 TAHAPAN</h4>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border-2 border-blue-50 relative overflow-hidden flex flex-col justify-center">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-1">Status Sistem</p>
            <div class="flex items-center gap-3">
                <h4 class="text-3xl font-black text-blue-900 tracking-tighter uppercase italic">Online</h4>
                <div class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid Paket --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($pakets as $paket)
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-blue-50 overflow-hidden hover:shadow-2xl hover:border-yellow-400 transition-all duration-500 group">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-4 bg-blue-50 rounded-2xl text-blue-600 group-hover:bg-yellow-400 group-hover:text-white transition-all duration-500 shadow-inner">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-black text-blue-900 mb-6 leading-tight min-h-[3.5rem] uppercase italic">{{ $paket->nama_paket }}</h3>
                    <div class="flex items-center justify-between pt-6 border-t-2 border-gray-50">
                        <div>
                            <p class="text-[8px] font-black text-gray-300 uppercase tracking-[0.2em] mb-0.5">Submisi</p>
                            <p class="text-sm font-black text-blue-900">{{ $paket->submissions_count ?? 0 }} <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter ml-1">Berkas</span></p>
                        </div>
                        <a href="{{ route('admin.paket.users', $paket->id) }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-yellow-400 hover:text-blue-900 transition-all active:scale-95">Monitor</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-20 rounded-[3rem] border-4 border-dashed border-blue-50 text-center">
                <p class="text-blue-200 font-black uppercase tracking-widest text-xs">Data Kosong</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL KELOLA: TETAP ADA SCROLL AGAR TIDAK MELEBIHI LAYAR --}}
    <div x-show="openManage" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-blue-900/80 backdrop-blur-xl" x-cloak>
        <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col border-t-[12px] border-yellow-400" @click.away="openManage = false">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-blue-50/30">
                <h3 class="text-2xl font-black text-blue-900 uppercase tracking-tighter italic">Manajemen Database</h3>
                <button @click="openManage = false" class="w-12 h-12 flex items-center justify-center rounded-full bg-white shadow-xl text-gray-400 hover:text-red-500 transition-all font-black text-2xl">&times;</button>
            </div>
            {{-- Scroll Khusus Modal Hapus/Edit Paket --}}
            <div class="p-8 overflow-y-auto space-y-4 custom-scrollbar">
                @foreach($pakets as $p)
                <div x-data="{ editing: false, newName: '{{ $p->nama_paket }}' }" class="flex items-center justify-between p-6 bg-white rounded-3xl border-2 border-blue-50 hover:border-yellow-200 transition-all">
                    <div class="flex-1 mr-4">
                        <template x-if="!editing">
                            <span class="font-black text-blue-900 text-sm tracking-tight italic uppercase">{{ $p->nama_paket }}</span>
                        </template>
                        <template x-if="editing">
                            <form action="{{ route('admin.paket.update', $p->id) }}" method="POST" class="flex gap-2 w-full">
                                @csrf @method('PUT')
                                <input type="text" name="nama_paket" x-model="newName" class="flex-1 p-3 bg-blue-50 border-none rounded-2xl text-sm font-black text-blue-900 focus:ring-4 focus:ring-yellow-300 outline-none">
                                <button type="submit" class="bg-blue-600 text-white px-5 rounded-2xl text-[10px] font-black uppercase shadow-lg">Update</button>
                            </form>
                        </template>
                    </div>
                    <div class="flex gap-2" x-show="!editing">
                        <button @click="editing = true" class="p-3 text-yellow-600 hover:bg-yellow-50 rounded-2xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 012 2h11a2 2 0 012-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <form action="{{ route('admin.paket.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus paket ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-3 text-red-500 hover:bg-red-50 rounded-2xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH PAKET --}}
    <div x-show="openModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-blue-900/80 backdrop-blur-xl" x-cloak>
        <div class="bg-white p-9 rounded-[2.5rem] shadow-2xl w-full max-w-md border-b-8 border-blue-600" @click.away="openModal = false">
            <h3 class="text-2xl font-black text-blue-900 mb-2 uppercase tracking-tighter italic text-center md:text-left">Buat Paket Baru</h3>
            <p class="text-gray-400 mb-8 text-[10px] font-bold uppercase tracking-widest text-center md:text-left">Sistem otomatis membuat 11 tahapan</p>
            
            <form action="{{ route('admin.paket.store') }}" method="POST">
                @csrf
                <div class="mb-8">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 text-left">Nama Paket Pekerjaan</label>
                    <input type="text" name="nama_paket" class="w-full p-5 bg-blue-50 border-none rounded-[1.5rem] focus:ring-4 focus:ring-yellow-400 outline-none transition font-black text-blue-900 text-sm italic uppercase" placeholder="Input Nama..." required>
                </div>
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-[1.2rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl border-b-4 border-blue-800 active:border-b-0 active:translate-y-1">Simpan Paket</button>
                    <button type="button" @click="openModal = false" class="w-full py-2 text-gray-400 font-black text-[10px] uppercase tracking-widest hover:text-gray-600">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Menghilangkan scrollbar pada body/halaman utama agar tampil full */
    body {
        overflow-x: hidden;
    }
    
    [x-cloak] { display: none !important; }
    
    /* Scrollbar hanya untuk isi modal manajemen */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
@endsection