@extends('layouts.app')

@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100">
        <h2 class="text-lg font-bold text-slate-800">Riwayat Penghapusan/Reset</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase">
                <tr>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Paket Pekerjaan</th>
                    <th class="px-6 py-4">Data User</th>
                    <th class="px-6 py-4">Dokumen Terhapus</th>
                    <th class="px-6 py-4">Eksekutor (Admin)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach(\DB::table('submission_histories')->latest()->get() as $log)
                <tr class="text-sm text-slate-600">
                    <td class="px-6 py-4">{{ date('d M Y, H:i', strtotime($log->created_at)) }}</td>
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $log->nama_paket }}</td>
                    <td class="px-6 py-4">{{ $log->nama_pengunggah }}</td>
                    <td class="px-6 py-4 text-center"><span class="bg-rose-100 text-rose-600 px-2 py-1 rounded text-xs font-bold">{{ $log->total_dokumen }}</span></td>
                    <td class="px-6 py-4 font-semibold text-indigo-600">{{ $log->di_eksekusi_oleh }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection