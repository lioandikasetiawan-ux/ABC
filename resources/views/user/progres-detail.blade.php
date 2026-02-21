@extends('layouts.user')

@section('content')
    <div class="w-full px-6 py-4">
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('user.progres.index') }}"
                class="inline-flex items-center text-xs font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-4 group">
                <svg class="w-4 h-4 mr-1 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2">
                    <path d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Progres
            </a>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-1 h-8 bg-indigo-600 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-slate-800">Status Verifikasi Admin</h2>
                    </div>
                    <div class="flex items-center gap-2 ml-4">
                        <span class="text-sm text-slate-500">Paket:</span>
                        <span class="text-sm font-semibold text-indigo-600">{{ $paket->nama_paket }}</span>
                    </div>
                </div>

                {{-- Progress Card --}}
                <div
                    class="bg-indigo-50 px-6 py-4 rounded-xl border border-indigo-100 flex items-center gap-4 min-w-[200px]">
                    <div class="flex-1">
                        <p class="text-xs font-medium text-indigo-600 mb-1">Dokumen Disetujui</p>
                        <div class="flex items-baseline gap-1">
                            <span
                                class="text-2xl font-bold text-indigo-700">{{ $submissions->whereIn('status', ['disetujui', 'verified'])->count() }}</span>
                            <span class="text-sm text-indigo-400">/11</span>
                        </div>
                        <div class="w-full h-1.5 bg-indigo-200 rounded-full mt-2">
                            <div class="h-full bg-indigo-600 rounded-full"
                                style="width: {{ ($submissions->whereIn('status', ['disetujui', 'verified'])->count() / 11) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-indigo-600 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Rincian --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 w-16 text-center">No</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500">Tahapan Dokumen</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 text-center w-32">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500">Catatan / Revisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @php
                            $labels = [
                                1 => 'Pernyataan Kesediaan Menerima Hibah',
                                2 => 'Permohonan Anggota Tim Internal',
                                3 => 'Pembentukan Tim Internal',
                                4 => 'Berita Acara Penelitian',
                                5 => 'Saran Teknis',
                                6 => 'Rekomendasi Teknis',
                                7 => 'Persetujuan Hibah',
                                8 => 'Penyusunan BAST & NPHD',
                                9 => 'SK Penghapusan',
                                10 => 'Transaksi SAKTI',
                                11 => 'Laporan KPKNL',
                            ];
                        @endphp

                        @for ($i = 1; $i <= 11; $i++)
                            @php
                                $sub = $submissions->get($i);
                                $status = $sub ? $sub->status : 'belum_upload';

                                $statusConfig = [
                                    'disetujui' => ['bg-emerald-100 text-emerald-700', 'CheckCircle'],
                                    'verified' => ['bg-emerald-100 text-emerald-700', 'CheckCircle'],
                                    'ditolak' => ['bg-rose-100 text-rose-700', 'XCircle'],
                                    'rejected' => ['bg-rose-100 text-rose-700', 'XCircle'],
                                    'pending' => ['bg-amber-100 text-amber-700', 'Clock'],
                                    'belum_upload' => ['bg-slate-100 text-slate-400', 'File'],
                                ];

                                $statusKey = in_array($status, ['disetujui', 'verified'])
                                    ? 'disetujui'
                                    : (in_array($status, ['ditolak', 'rejected'])
                                        ? 'ditolak'
                                        : ($status == 'pending'
                                            ? 'pending'
                                            : 'belum_upload'));

                                $statusClass = $statusConfig[$statusKey][0];
                                $statusIcon = $statusConfig[$statusKey][1];

                                $statusLabel = [
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    'pending' => 'Menunggu',
                                    'belum_upload' => 'Kosong',
                                ][$statusKey];
                            @endphp

                            <tr class="hover:bg-slate-50/80 transition-colors">
                                {{-- Nomor --}}
                                <td class="px-6 py-6 text-center">
                                    <span
                                        class="text-lg font-bold text-slate-300">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>

                                {{-- Tahapan Dokumen --}}
                                <td class="px-6 py-6">
                                    <p class="text-sm font-medium text-slate-800 mb-2">{{ $labels[$i] }}</p>
                                    @if ($statusKey == 'ditolak')
                                        <a href="{{ route('user.step', [$paket->id, $i]) }}"
                                            class="inline-flex items-center gap-1.5 text-xs font-medium text-rose-600 hover:text-rose-700 transition-colors group">
                                            <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                </path>
                                            </svg>
                                            <span>Unggah Ulang Dokumen</span>
                                        </a>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-6 text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $statusClass }} text-xs font-medium rounded-lg">
                                        @switch($statusIcon)
                                            @case('CheckCircle')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    stroke-width="2">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @break

                                            @case('XCircle')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    stroke-width="2">
                                                    <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                            @break

                                            @case('Clock')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    stroke-width="2">
                                                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @break

                                            @default
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    stroke-width="2">
                                                    <path
                                                        d="M9 12h6m-6 4h6m2-10H7a2 2 0 00-2 2v14l4-4h10a2 2 0 002-2V6a2 2 0 00-2-2z">
                                                    </path>
                                                </svg>
                                        @endswitch
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Catatan / Revisi --}}
                                <td class="px-6 py-6">
                                    @if ($sub && $sub->catatan_admin)
                                        <div
                                            class="p-4 bg-slate-50 rounded-lg border-l-4 {{ $statusKey == 'ditolak' ? 'border-rose-500' : 'border-indigo-500' }}">
                                            <div class="flex items-start gap-2">
                                                <svg class="w-4 h-4 mt-0.5 {{ $statusKey == 'ditolak' ? 'text-rose-500' : 'text-indigo-500' }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    stroke-width="2">
                                                    <path
                                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                                    </path>
                                                </svg>
                                                <p class="text-sm text-slate-600 leading-relaxed">{{ $sub->catatan_admin }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Informasi Tambahan --}}
        <div class="mt-6 flex gap-4 justify-end">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                <span class="text-xs text-slate-500">Disetujui</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                <span class="text-xs text-slate-500">Menunggu</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                <span class="text-xs text-slate-500">Ditolak</span>
            </div>
        </div>
    </div>
@endsection
