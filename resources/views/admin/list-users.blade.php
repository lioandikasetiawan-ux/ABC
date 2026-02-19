@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Unggahan: {{ $paket->nama_paket }}</h2>
        <p class="text-gray-500">Menampilkan seluruh satker yang mengunggah pada <strong>Step {{ $stepNumber }}</strong></p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Nama User / Satker</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Paket</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">File yang Diupload</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center">Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                    @foreach($user->submissions as $sub)
                    <tr class="hover:bg-blue-50/30 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-xs mr-3">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-700">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $paket->nama_paket }}
                        </td>
                        <td class="px-6 py-4">
                            @if($sub->file_path)
                                <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" 
                                   class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded-lg transition border border-gray-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Lihat Dokumen
                                </a>
                            @else
                                <span class="text-gray-400 italic text-xs">Tidak ada file</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($sub->status == 'approved')
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-full">Disetujui</span>
                            @elseif($sub->status == 'rejected')
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold uppercase rounded-full">Ditolak</span>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold uppercase rounded-full">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2" x-data="{ openReject: false }">
                                <form action="{{ route('admin.verify.submit', $sub->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="p-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow-sm transition" title="Approve">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </form>

                                <button @click="openReject = true" class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-sm transition" title="Tolak">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>

                                <template x-if="openReject">
                                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                        <div class="bg-white p-6 rounded-2xl w-96 text-left shadow-2xl border border-gray-100">
                                            <h3 class="text-lg font-bold text-gray-800 mb-2">Tolak Submission?</h3>
                                            <p class="text-sm text-gray-500 mb-4">Berikan alasan penolakan agar user dapat memperbaiki dokumen.</p>
                                            
                                            <form action="{{ route('admin.verify.submit', $sub->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <textarea name="catatan_admin" class="w-full border-gray-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500 mb-4" rows="3" placeholder="Contoh: File tidak terbaca atau data tidak sesuai." required></textarea>
                                                
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" @click="openReject = false" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Batal</button>
                                                    <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Kirim Penolakan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Belum ada user yang mengunggah dokumen pada tahap ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection