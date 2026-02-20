@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-4">
    {{-- Alert Success / Error --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 text-[10px] font-black uppercase italic shadow-sm rounded-r-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 bg-white p-8 rounded-[35px] border border-gray-100 shadow-sm">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-[10px] font-black text-blue-600 uppercase tracking-widest hover:text-yellow-500 transition-colors mb-3">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Dashboard
            </a>
            <h2 class="text-2xl font-black text-blue-900 uppercase italic leading-none">Verifikasi: {{ $user->name }}</h2>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-2">Paket: {{ $paket->nama_paket }}</p>
        </div>
        <div class="bg-blue-50 px-8 py-4 rounded-[25px] border border-blue-100 text-center">
            <span class="block text-[9px] font-black text-blue-400 uppercase tracking-[0.2em] mb-1">Total Progress</span>
            <span class="text-3xl font-black text-blue-900 italic">{{ $submissions->whereIn('status', ['disetujui', 'verified'])->count() }}<span class="opacity-30">/11</span></span>
        </div>
    </div>

    <div class="bg-white rounded-[35px] border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-16 text-center">No</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tahapan & Dokumen</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-40 text-center">Status</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-1/3">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @php
                    $labels = [1 => 'Pernyataan Kesediaan Menerima Hibah', 2 => 'Permohonan Anggota Tim Internal', 3 => 'Pembentukan Tim Internal', 4 => 'Berita Acara Penelitian', 5 => 'Saran Teknis', 6 => 'Rekomendasi Teknis', 7 => 'Persetujuan Hibah', 8 => 'Penyusunan BAST & NPHD', 9 => 'SK Penghapusan', 10 => 'Transaksi SAKTI', 11 => 'Laporan KPKNL'];
                @endphp

                @for ($i = 1; $i <= 11; $i++)
                    @php 
                        $sub = $submissions->where('step_number', $i)->first(); 
                        $fileList = [];
                        
                        if ($sub && $sub->file_path) {
                            if (is_array($sub->file_path)) {
                                $fileList = $sub->file_path;
                            } else {
                                $decoded = json_decode($sub->file_path, true);
                                $fileList = is_array($decoded) ? $decoded : [$sub->file_path];
                            }
                        }
                    @endphp
                    <tr class="hover:bg-blue-50/20 transition-colors">
                        <td class="px-8 py-6 text-center font-black text-gray-300 italic text-xl">{{ $i }}</td>
                        <td class="px-8 py-6">
                            <h3 class="text-[11px] font-black text-blue-900 uppercase italic mb-3 tracking-wide">{{ $labels[$i] }}</h3>
                            <div class="flex flex-col gap-2"> {{-- Diubah ke flex-col agar nama file panjang tidak berantakan --}}
                                @forelse($fileList as $index => $file)
                                    <a href="{{ asset('storage/'.$file) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-900 text-white text-[9px] font-black uppercase rounded-xl hover:bg-yellow-400 hover:text-blue-900 transition-all shadow-sm italic w-fit max-w-full">
                                        <svg class="w-3 h-3 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        <span class="truncate">{{ basename($file) }}</span>
                                    </a>
                                @empty
                                    <span class="text-[9px] text-gray-300 font-bold uppercase italic tracking-widest">Belum Ada Berkas</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($sub)
                                @php
                                    $statusLabel = $sub->status;
                                    $colorClass = 'bg-yellow-400 text-white';
                                    if(in_array($sub->status, ['verified', 'disetujui'])) {
                                        $statusLabel = 'Disetujui';
                                        $colorClass = 'bg-green-500 text-white';
                                    } elseif($sub->status == 'ditolak') {
                                        $statusLabel = 'Ditolak';
                                        $colorClass = 'bg-red-500 text-white';
                                    }
                                @endphp
                                <span class="px-4 py-1.5 text-[9px] font-black uppercase rounded-lg {{ $colorClass }} whitespace-nowrap">
                                    {{ $statusLabel }}
                                </span>
                            @else
                                <span class="text-gray-200">—</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            @if($sub)
                                <form action="{{ route('admin.verify.submit', $sub->id) }}" method="POST" class="space-y-3" id="form-{{ $sub->id }}">
                                    @csrf
                                    <input type="text" name="catatan_admin" id="catatan_{{ $sub->id }}" value="{{ $sub->catatan_admin }}" placeholder="Wajib diisi jika menolak..." class="text-[10px] font-bold p-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-100 outline-none text-gray-600 w-full shadow-inner">
                                    
                                    <div class="flex gap-2">
                                        <button type="submit" onclick="document.getElementById('status_hidden_{{ $sub->id }}').value='disetujui'" class="flex-1 py-2 bg-green-100 text-green-700 text-[9px] font-black uppercase rounded-xl hover:bg-green-500 hover:text-white transition-all italic border border-green-200">Setuju</button>
                                        <button type="button" onclick="handleReject({{ $sub->id }})" class="flex-1 py-2 bg-red-100 text-red-700 text-[9px] font-black uppercase rounded-xl hover:bg-red-500 hover:text-white transition-all italic border border-red-200">Tolak</button>
                                    </div>
                                    <input type="hidden" name="status" id="status_hidden_{{ $sub->id }}">
                                </form>
                            @endif
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

<script>
    function handleReject(subId) {
        const catatan = document.getElementById('catatan_' + subId);
        const form = document.getElementById('form-' + subId);
        const hiddenStatus = document.getElementById('status_hidden_' + subId);

        if (!catatan.value.trim()) {
            catatan.classList.add('ring-2', 'ring-red-500', 'bg-red-50');
            catatan.placeholder = "ALASAN WAJIB DIISI SEBELUM MENOLAK!";
            catatan.focus();
            return;
        }

        hiddenStatus.value = 'ditolak';
        form.submit();
    }
</script>
@endsection