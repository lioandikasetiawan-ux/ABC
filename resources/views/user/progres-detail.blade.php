@extends('layouts.user')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('user.progres.index') }}" class="inline-flex items-center text-[10px] font-black text-blue-600 uppercase tracking-widest hover:gap-2 transition-all mb-2">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Progres
            </a>
            <h2 class="text-2xl font-black text-gray-900 uppercase italic leading-none">Status Verifikasi Admin</h2>
            <p class="text-sm text-gray-500 mt-1">Paket: <span class="font-bold text-blue-600">{{ $paket->nama_paket }}</span></p>
        </div>
        
        <div class="bg-white px-6 py-3 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="text-right">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Dokumen Disetujui</p>
                <p class="text-xl font-black text-blue-900 italic">{{ $submissions->whereIn('status', ['disetujui', 'verified'])->count() }}/11</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    {{-- Tabel Rincian --}}
    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-gray-100">
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-16 text-center">No</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tahapan Dokumen</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Catatan/Revisi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @php
                    $labels = [1 => 'Pernyataan Kesediaan Menerima Hibah', 2 => 'Permohonan Anggota Tim Internal', 3 => 'Pembentukan Tim Internal', 4 => 'Berita Acara Penelitian', 5 => 'Saran Teknis', 6 => 'Rekomendasi Teknis', 7 => 'Persetujuan Hibah', 8 => 'Penyusunan BAST & NPHD', 9 => 'SK Penghapusan', 10 => 'Transaksi SAKTI', 11 => 'Laporan KPKNL'];
                @endphp

                @for ($i = 1; $i <= 11; $i++)
                    @php 
                        $sub = $submissions->get($i);
                        $status = $sub ? $sub->status : 'belum_upload';
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6 text-center text-sm font-black text-gray-300 italic">{{ $i }}</td>
                        <td class="px-8 py-6">
                            <p class="text-sm font-bold text-gray-800">{{ $labels[$i] }}</p>
                            @if($status == 'ditolak')
                                <a href="{{ route('user.step', [$paket->id, $i]) }}" class="inline-flex items-center text-[10px] font-black text-red-500 uppercase mt-1 hover:underline">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Unggah Ulang Dokumen
                                </a>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($status == 'disetujui' || $status == 'verified')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase bg-green-50 text-green-600 border border-green-100">Disetujui</span>
                            @elseif($status == 'ditolak' || $status == 'rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase bg-red-50 text-red-600 border border-red-100 shadow-sm animate-pulse">Ditolak</span>
                            @elseif($status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase bg-yellow-50 text-yellow-600 border border-yellow-100">Menunggu</span>
                            @else
                                <span class="text-[10px] font-bold text-gray-200 uppercase italic">Kosong</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            @if($sub && $sub->catatan_admin)
                                <div class="p-3 bg-slate-50 rounded-xl border-l-4 {{ $status == 'ditolak' ? 'border-red-500' : 'border-blue-500' }}">
                                    <p class="text-[11px] text-gray-600 font-medium italic leading-relaxed">"{{ $sub->catatan_admin }}"</p>
                                </div>
                            @else
                                <span class="text-[10px] text-gray-300 italic">Tidak ada catatan</span>
                            @endif
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection