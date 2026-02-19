@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ openModal: false, openManage: false }">
    {{-- Header Section --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800">Monitoring Hibah</h2>
            <p class="text-gray-500 mt-1">Pantau progres unggahan dokumen berdasarkan 11 tahapan hibah.</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-xl shadow-sm hover:bg-gray-50 transition">
                Export Laporan
            </button>
            <button @click="openModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition">
                + Tambah Paket
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        {{-- Card Total Paket (Bisa diklik untuk Kelola) --}}
        <div @click="openManage = true" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 cursor-pointer hover:border-blue-400 hover:shadow-md transition-all group relative overflow-hidden">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Total Paket</p>
                    <h4 class="text-2xl font-bold text-gray-800 group-hover:text-blue-600">{{ $pakets->count() }}</h4>
                </div>
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-blue-500 mt-2 font-bold opacity-0 group-hover:opacity-100 transition-opacity">Klik untuk Kelola Paket →</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Total Tahapan</p>
            <h4 class="text-2xl font-bold text-yellow-600">11 Langkah</h4> 
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Status Sistem</p>
            <h4 class="text-2xl font-bold text-blue-600">Aktif</h4>
        </div>
    </div>

    {{-- Grid Paket --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($pakets as $paket)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold px-2 py-1 bg-gray-100 text-gray-500 rounded-lg">ID: #{{ $paket->id }}</span>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-800 mb-1 leading-tight">{{ $paket->nama_paket }}</h3>
                    <p class="text-sm text-gray-400 mb-6 italic text-xs">Mekanisme Hibah Persediaan</p>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                        <div class="text-sm text-gray-600">
                            <span class="font-bold text-gray-800">{{ $paket->submissions_count ?? 0 }}</span> Submisi
                        </div>
                        <a href="{{ route('admin.paket.users', $paket->id) }}" 
                           class="text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            Lihat Detail
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 rounded-2xl border border-dashed border-gray-300 text-center text-gray-500">
                Belum ada paket hibah yang terdaftar.
            </div>
        @endforelse
    </div>

    {{-- Modal Kelola (Edit/Hapus) --}}
    <div x-show="openManage" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[80vh] overflow-hidden flex flex-col" @click.away="openManage = false">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-xl font-extrabold text-gray-800">Daftar Semua Paket</h3>
                <button @click="openManage = false" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-6 overflow-y-auto space-y-3">
                @foreach($pakets as $p)
                <div x-data="{ editing: false, newName: '{{ $p->nama_paket }}' }" class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 group">
                    <div class="flex-1 mr-4">
                        <template x-if="!editing">
                            <span class="font-bold text-gray-700">{{ $p->nama_paket }}</span>
                        </template>
                        <template x-if="editing">
                            <form action="{{ route('admin.paket.update', $p->id) }}" method="POST" class="flex gap-2">
                                @csrf @method('PUT')
                                <input type="text" name="nama_paket" x-model="newName" class="w-full p-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                                <button type="submit" class="bg-blue-600 text-white px-3 rounded-lg text-xs">OK</button>
                                <button type="button" @click="editing = false" class="text-xs text-gray-400">Batal</button>
                            </form>
                        </template>
                    </div>
                    <div class="flex gap-2" x-show="!editing">
                        <button @click="editing = true" class="p-2 text-yellow-600 hover:bg-yellow-100 rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <form action="{{ route('admin.paket.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus paket ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Modal Tambah Paket --}}
    <div x-show="openModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40" x-cloak>
        <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md" @click.away="openModal = false">
            <h3 class="text-2xl font-extrabold text-gray-800 mb-2">Buat Paket Baru</h3>
            <p class="text-gray-500 mb-6 text-sm">Paket baru akan otomatis memiliki 11 tahapan standar.</p>
            
            <form action="{{ route('admin.paket.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Paket Pekerjaan</label>
                    <input type="text" name="nama_paket" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Contoh: Hibah Pembangunan Jaringan..." required>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="openModal = false" class="px-6 py-3 text-gray-400 font-bold hover:text-gray-600">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition">Simpan Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<style>
    [x-cloak] { display: none !important; }
</style>