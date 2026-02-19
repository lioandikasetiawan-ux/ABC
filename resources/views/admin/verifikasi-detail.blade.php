@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <a href="{{ route('admin.paket.users', $paket->id) }}" class="flex items-center text-sm text-blue-600 mb-4 hover:underline">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Daftar User
    </a>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-tight">Verifikasi: {{ $user->name }}</h2>
                <p class="text-gray-500 italic">Paket Hibah: {{ $paket->nama_paket }}</p>
            </div>
            <div class="text-right">
                <span class="text-xs font-semibold text-gray-400 uppercase">Total Progress</span>
                <p class="text-xl font-bold text-blue-600">{{ $submissions->where('status', 'disetujui')->count() }}/11</p>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @php
            // Label tahapan sesuai dokumen mekanisme hibah
            $labels = [
                1 => 'Pernyataan Kesediaan Menerima Hibah',
                2 => 'Permohonan Anggota Tim Internal (Sesditjen SDA)',
                3 => 'Pembentukan Tim Internal (Kepala Balai)',
                4 => 'Berita Acara Penelitian Tim Internal',
                5 => 'Saran Teknis Tim Internal',
                6 => 'Rekomendasi Teknis & Permohonan Persetujuan',
                7 => 'Persetujuan Hibah (Sekjen An. Menteri)',
                8 => 'Penyusunan BAST & Naskah Hibah (Multiple File)',
                9 => 'SK Penetapan Penghapusan',
                10 => 'Transaksi Hibah Keluar (SAKTI)',
                11 => 'Laporan Pelaksanaan Hibah (KPKNL)'
            ];
        @endphp

        @for ($i = 1; $i <= 11; $i++)
            @php $sub = $submissions->get($i); @endphp
            
            <div class="group bg-white rounded-lg shadow-sm border-l-8 transition-all {{ $sub ? ($sub->status == 'disetujui' ? 'border-green-500' : ($sub->status == 'ditolak' ? 'border-red-500' : 'border-yellow-500')) : 'border-gray-200' }}">
                <div class="p-5">
                    <div class="flex justify-between items-start">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 font-bold text-gray-600 border">
                                {{ $i }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $labels[$i] }}</h3>
                                @if($sub)
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($sub->file_path as $index => $file)
                                            <a href="{{ asset('storage/'.$file) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 text-xs rounded-md border border-blue-200 hover:bg-blue-100 transition-colors">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                Lihat Dokumen {{ count($sub->file_path) > 1 ? ($index + 1) : '' }}
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 italic">Belum ada unggahan berkas</span>
                                @endif
                            </div>
                        </div>

                        @if($sub)
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $sub->status == 'disetujui' ? 'bg-green-100 text-green-700' : ($sub->status == 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ strtoupper($sub->status) }}
                            </span>
                        @endif
                    </div>

                    @if($sub && $sub->status != 'disetujui')
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <form action="{{ route('admin.verify.submit', $sub->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <label class="text-xs font-semibold text-gray-500 uppercase">Catatan Verifikasi</label>
                                <textarea name="catatan_admin" rows="2" placeholder="Berikan alasan jika dokumen ditolak..." class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 transition-all">{{ $sub->catatan_admin }}</textarea>
                                
                                <div class="flex justify-end gap-3">
                                    <button name="status" value="ditolak" class="px-5 py-2 bg-white border border-red-600 text-red-600 rounded-lg text-sm font-bold hover:bg-red-50 transition-colors">Tolak Dokumen</button>
                                    <button name="status" value="disetujui" class="px-5 py-2 bg-green-600 border border-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 shadow-sm transition-colors">Setujui Dokumen</button>
                                </div>
                            </form>
                        </div>
                    @elseif($sub && $sub->status == 'disetujui')
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600 border border-dashed">
                            <strong>Catatan Admin:</strong> {{ $sub->catatan_admin ?: 'Tidak ada catatan.' }}
                        </div>
                    @endif
                </div>
            </div>
        @endfor
    </div>
</div>
@endsection