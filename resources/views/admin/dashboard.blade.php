@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-2" x-data="{ openModal: false, openManage: false }">

    {{-- NOTIFIKASI --}}
    <div x-data="{ show: false, message: '', type: 'success', init() { @if(session('success')) this.showNotification('{{ session('success') }}', 'success'); @endif } , showNotification(msg, type) { this.message = msg; this.type = type; this.show = true; setTimeout(() => { this.show = false }, 3000); } }" x-show="show" class="fixed top-6 left-1/2 -translate-x-1/2 z-[100]" x-cloak>
        <div :class="type === 'success' ? 'border-blue-600' : 'border-red-600'" class="flex items-center gap-3 px-6 py-3 bg-white border-l-4 rounded-2xl shadow-xl min-w-[320px]">
            <p class="text-[11px] font-bold text-gray-700 uppercase" x-text="message"></p>
        </div>
    </div>

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8 border-b-2 border-yellow-400 pb-4">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                <h2 class="text-xl font-black text-blue-900 uppercase italic">Monitoring Progres Hibah</h2>
            </div>
            <p class="text-gray-400 font-bold text-[9px] ml-4 tracking-widest uppercase">Admin Verification Desk</p>
        </div>
        <div class="flex gap-3">
            <button @click="openManage = true" class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-gray-50 transition">Kelola Database</button>
            <button @click="openModal = true" class="bg-blue-600 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-blue-100 hover:bg-blue-700 transition">+ Tambah Paket</button>
        </div>
    </div>

    {{-- TABEL MONITORING --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Paket Pekerjaan</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">PIC / Pengunggah</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Progres Upload</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Progres Verifikasi</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pakets as $paket)
                <tr class="hover:bg-blue-50/30 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-blue-900 uppercase italic">{{ $paket->nama_paket }}</span>
                        </div>
                    </td>
                    
                    <td class="px-6 py-5">
                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-tight">
                            {{ $paket->user_name }}
                        </span>
                    </td>

                    <td class="px-6 py-5 text-center">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-600 rounded-full">
                            <span class="text-xs font-black">{{ $paket->uploaded_count }}/11</span>
                            <div class="w-12 h-1.5 bg-blue-200 rounded-full overflow-hidden">
                                <div class="bg-blue-600 h-full" style="width: {{ ($paket->uploaded_count/11)*100 }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-50 text-green-600 rounded-full">
                            <span class="text-xs font-black">{{ $paket->verified_count }}/{{ $paket->uploaded_count ?: '0' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-5 text-center">
                        @php
                            $color = 'bg-gray-100 text-gray-400';
                            if($paket->status_label == 'SELESAI') $color = 'bg-green-500 text-white';
                            if($paket->status_label == 'PERLU VERIF') $color = 'bg-yellow-400 text-white';
                            // Tambahan warna untuk status On Progress agar lebih jelas
                            if($paket->status_label == 'ON PROGRESS') $color = 'bg-blue-100 text-blue-500';
                        @endphp
                        {{-- whitespace-nowrap memastikan teks tidak turun ke bawah --}}
                        <span class="inline-block px-3 py-1 {{ $color }} text-[9px] font-black uppercase rounded-lg italic whitespace-nowrap">
                            {{ $paket->status_label }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right">
                        @if($paket->user_id)
                            <a href="{{ route('admin.paket.detail', [$paket->id, $paket->user_id]) }}" class="inline-flex items-center gap-2 bg-blue-900 text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-yellow-400 hover:text-blue-900 transition-all shadow-md italic">
                                Detail Verifikasi
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        @else
                            <button disabled class="opacity-30 inline-flex items-center gap-2 bg-gray-400 text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest italic">
                                Belum Ada Data
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-12 text-center text-gray-400 font-bold uppercase text-[10px] tracking-widest">Belum ada data paket masuk</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL KELOLA DATABASE --}}
    <div x-show="openManage" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-blue-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg max-h-[80vh] overflow-hidden flex flex-col border-t-8 border-yellow-400" @click.away="openManage = false">
            <div class="p-5 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-black text-blue-900 uppercase italic">Edit/Hapus Paket</h3>
                <button @click="openManage = false" class="text-gray-300 hover:text-red-500 text-xl">&times;</button>
            </div>
            <div class="p-5 overflow-y-auto space-y-2 custom-scrollbar">
                @foreach($pakets as $p)
                <div x-data="{ editing: false, newName: '{{ $p->nama_paket }}' }" class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-transparent hover:border-yellow-200 transition-all">
                    <div class="flex-1 mr-3">
                        <template x-if="!editing">
                            <span class="font-black text-blue-900 text-[11px] uppercase italic">{{ $p->nama_paket }}</span>
                        </template>
                        <template x-if="editing">
                            <form action="{{ route('admin.paket.update', $p->id) }}" method="POST" class="flex gap-2">
                                @csrf @method('PUT')
                                <input type="text" name="nama_paket" x-model="newName" class="flex-1 p-2 bg-white rounded-xl text-[10px] font-black uppercase outline-none ring-1 ring-blue-100">
                                <button type="submit" class="bg-blue-600 text-white px-3 rounded-xl text-[8px] font-black uppercase">Save</button>
                            </form>
                        </template>
                    </div>
                    <div class="flex gap-1" x-show="!editing">
                        <button @click="editing = true" class="p-2 text-yellow-600 hover:bg-white rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <form action="{{ route('admin.paket.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-white rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
        <div class="bg-white p-7 rounded-[2.5rem] shadow-2xl w-full max-w-sm border-b-8 border-blue-600" @click.away="openModal = false">
            <h3 class="text-xl font-black text-blue-900 mb-1 uppercase italic">Buat Paket Baru</h3>
            <p class="text-gray-400 mb-6 text-[8px] font-bold uppercase tracking-widest">Otomatis membuat 11 Tahapan input</p>
            <form action="{{ route('admin.paket.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Paket Pekerjaan</label>
                    <input type="text" name="nama_paket" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-yellow-400 outline-none font-black text-blue-900 text-xs italic uppercase" placeholder="CONTOH: PEMBANGUNAN BENDUNGAN..." required>
                </div>
                <div class="flex flex-col gap-2">
                    <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100">Simpan Paket</button>
                    <button type="button" @click="openModal = false" class="w-full py-2 text-gray-400 font-black text-[8px] uppercase tracking-widest">Tutup</button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
    body { background-color: #f8fafc; }
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
@endsection