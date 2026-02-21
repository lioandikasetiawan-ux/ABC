@extends('layouts.user')

@section('content')
    <div class="w-full px-6 py-4">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-1 h-8 bg-indigo-600 rounded-full"></div>
                <h2 class="text-2xl font-bold text-slate-800">
                    Pantau <span class="text-indigo-600">Progres Hibah</span>
                </h2>
            </div>
            <p class="text-sm text-slate-500 ml-4">Status verifikasi dokumen secara real-time untuk setiap paket pekerjaan
            </p>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500">Nama Paket Pekerjaan</th>
                            <th class="px-4 py-4 text-xs font-semibold text-slate-500 text-center">Tahapan Selesai</th>
                            <th class="px-4 py-4 text-xs font-semibold text-slate-500 text-center">Progres Verifikasi</th>
                            <th class="px-4 py-4 text-xs font-semibold text-slate-500 text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500">Visual Progres</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($pakets as $p)
                            @php
                                $progressPercent = round(($p->step_verifikasi / 11) * 100);
                                $uploadPercent = round(($p->step_selesai / 11) * 100);

                                $statusConfig = [
                                    'selesai' => ['bg-emerald-100 text-emerald-700', 'CheckCircle'],
                                    'terpenuhi' => [
                                        'bg-emerald-50 text-emerald-600 border border-emerald-200',
                                        'Check',
                                    ],
                                    'on_progress' => ['bg-blue-100 text-blue-700', 'Clock'],
                                    'belum_mulai' => ['bg-slate-100 text-slate-500', 'File'],
                                ];

                                if ($p->step_verifikasi >= 11) {
                                    $statusKey = 'selesai';
                                    $statusLabel = 'Selesai';
                                } elseif ($p->step_selesai >= 11) {
                                    $statusKey = 'terpenuhi';
                                    $statusLabel = 'Terpenuhi';
                                } elseif ($p->step_selesai > 0) {
                                    $statusKey = 'on_progress';
                                    $statusLabel = 'On Progress';
                                } else {
                                    $statusKey = 'belum_mulai';
                                    $statusLabel = 'Belum Mulai';
                                }

                                $statusClass = $statusConfig[$statusKey][0];
                                $statusIcon = $statusConfig[$statusKey][1];
                            @endphp

                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                {{-- Nama Paket --}}
                                <td class="px-6 py-5">
                                    <span class="text-sm font-medium text-slate-800">{{ $p->nama_paket }}</span>
                                </td>

                                {{-- Tahapan Selesai --}}
                                <td class="px-4 py-5 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-lg font-bold text-slate-700">
                                            {{ $p->step_selesai }}<span
                                                class="text-sm font-medium text-slate-400">/11</span>
                                        </span>
                                        <div class="w-16 h-1 bg-slate-100 rounded-full mt-1 overflow-hidden">
                                            <div class="h-full bg-indigo-400 rounded-full"
                                                style="width: {{ $uploadPercent }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Progres Verifikasi --}}
                                <td class="px-4 py-5 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span
                                            class="text-lg font-bold {{ $p->step_verifikasi == 11 ? 'text-emerald-600' : 'text-indigo-600' }}">
                                            {{ $p->step_verifikasi }}<span
                                                class="text-sm font-medium text-slate-400">/11</span>
                                        </span>
                                        <div class="w-16 h-1 bg-slate-100 rounded-full mt-1 overflow-hidden">
                                            <div class="h-full {{ $p->step_verifikasi == 11 ? 'bg-emerald-500' : 'bg-indigo-500' }} rounded-full"
                                                style="width: {{ $progressPercent }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-5 text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $statusClass }} text-xs font-medium rounded-lg">
                                        @switch($statusIcon)
                                            @case('CheckCircle')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    stroke-width="2">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @break

                                            @case('Check')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    stroke-width="2">
                                                    <path d="M5 13l4 4L19 7"></path>
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

                                {{-- Visual Progres --}}
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500
                                        {{ $p->step_verifikasi == 11 ? 'bg-emerald-500' : 'bg-indigo-500' }}"
                                                style="width: {{ $progressPercent }}%">
                                            </div>
                                        </div>
                                        <span
                                            class="text-xs font-semibold text-slate-600 min-w-[40px]">{{ $progressPercent }}%</span>
                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        {{-- Detail Button --}}
                                        <a href="{{ route('user.progres.detail', $p->id) }}"
                                            class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-colors"
                                            title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                stroke-width="2">
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        {{-- Kelola Button (if not fully verified) --}}
                                        @if ($p->step_verifikasi < 11)
                                            <a href="{{ route('user.step', [$p->id, 1]) }}"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-medium hover:bg-indigo-700 transition-all shadow-sm hover:shadow"
                                                title="Kelola Dokumen">
                                                <span>Kelola</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2.5">
                                                    <path d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" stroke-width="2">
                                                <path
                                                    d="M9 12h6m-6 4h6m2-10H7a2 2 0 00-2 2v14l4-4h10a2 2 0 002-2V6a2 2 0 00-2-2z">
                                                </path>
                                            </svg>
                                            <p class="text-slate-400 font-medium">Belum ada paket pekerjaan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Legend --}}
            <div class="mt-6 flex flex-wrap gap-4 justify-end">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-xs text-slate-500">Selesai</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                    <span class="text-xs text-slate-500">Terpenuhi</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                    <span class="text-xs text-slate-500">On Progress</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                    <span class="text-xs text-slate-500">Belum Mulai</span>
                </div>
            </div>
        </div>
    @endsection
