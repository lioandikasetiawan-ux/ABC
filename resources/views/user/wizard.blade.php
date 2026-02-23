@extends('layouts.user')

@section('content')
    <div class="w-full px-6 py-4">
        {{-- Progress Steps --}}
        <div class="mb-8 relative">
            <div class="absolute top-4 left-0 w-full h-0.5 bg-slate-200 -z-10"></div>

            <div class="flex justify-between overflow-x-auto pb-2 gap-1">
                @for ($i = 1; $i <= 11; $i++)
                    @php
                        $isCurrent = $step == $i;
                        $userSubmissions = $paket->submissions->where('user_id', auth()->id());

                        // Cek status spesifik per step
                        $stepSubmission = $userSubmissions->where('step_number', $i)->first();
                        $isRejected = $stepSubmission && $stepSubmission->status == 'ditolak';
                        $isCompleted =
                            $stepSubmission &&
                            $stepSubmission->file_path &&
                            ($stepSubmission->status == 'disetujui' || $stepSubmission->status == 'verified');

                        $maxStepUser = $userSubmissions->whereNotNull('file_path')->max('step_number') ?? 0;
                        $canNavigate = $i <= $maxStepUser + 1 || $isRejected; // Bisa navigasi jika sudah diisi atau jika ditolak (untuk revisi)
                    @endphp

                    <div class="flex flex-col items-center min-w-[45px] relative z-10">
                        @if ($canNavigate)
                            <a href="{{ route('user.step', [$paket->id, $i]) }}"
                                class="w-9 h-9 rounded-xl flex items-center justify-center font-semibold text-sm transition-all
                                {{ $isCurrent
                                    ? ($isRejected
                                        ? 'bg-rose-600 text-white ring-4 ring-rose-100'
                                        : 'bg-indigo-600 text-white ring-4 ring-indigo-100')
                                    : ($isRejected
                                        ? 'bg-rose-500 text-white hover:bg-rose-600'
                                        : ($isCompleted
                                            ? 'bg-emerald-500 text-white hover:bg-emerald-600'
                                            : 'bg-slate-100 text-indigo-600 hover:bg-indigo-100')) }}">

                                @if ($isRejected)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2.5">
                                        <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @elseif ($isCompleted && !$isCurrent)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2.5">
                                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @else
                                    <span class="text-xs">{{ $i }}</span>
                                @endif
                            </a>
                        @else
                            <div
                                class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 cursor-not-allowed">
                                <span class="text-xs">{{ $i }}</span>
                            </div>
                        @endif

                        <span
                            class="text-[9px] font-medium mt-1.5
                        {{ $isCurrent ? ($isRejected ? 'text-rose-600' : 'text-indigo-600') : ($isRejected ? 'text-rose-500' : ($isCompleted ? 'text-emerald-600' : 'text-slate-400')) }}">
                            {{ $isRejected ? 'Revisi' : 'S' . $i }}
                        </span>
                    </div>

                    @if ($i < 11)
                        <div
                            class="flex-1 h-0.5 mt-4 {{ $isRejected ? 'bg-rose-200' : ($isCompleted ? 'bg-emerald-500' : 'bg-slate-200') }}">
                        </div>
                    @endif
                @endfor
            </div>
        </div>

        {{-- Main Card --}}
        <div class="max-w-3xl mx-auto">
            {{-- Rejection Note (Hanya muncul jika step saat ini ditolak) --}}
            @if ($submission && $submission->status == 'ditolak')
                <div
                    class="mb-4 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 shadow-sm shadow-rose-100">
                    <div class="w-8 h-8 rounded-lg bg-rose-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-rose-800">Catatan Revisi:</h4>
                        <p class="text-xs text-rose-700 mt-1 leading-relaxed italic">
                            "{{ $submission->catatan_admin ?? 'Nyuwun pangapunten, berkas dereng saged dipun tampi. Mangga dipun teliti malih lan dipun unggah ulang.' }}"
                        </p>
                    </div>
                </div>
            @endif
            

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                {{-- Header --}}
                <div
                    class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <div
                                class="w-1 h-5 {{ $submission && $submission->status == 'ditolak' ? 'bg-rose-600' : 'bg-indigo-600' }} rounded-full">
                            </div>
                            <h2 class="text-lg font-semibold text-slate-800">Tahap {{ $step }}</h2>
                        </div>
                        <p class="text-sm text-slate-500 ml-3">{{ $paket->nama_paket }}</p>
                    </div>

                    {{-- Progress --}}
                    @php
                        $progressPercent = round(($totalCompleted / 11) * 100);
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $progressPercent }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-indigo-600">{{ $progressPercent }}%</span>
                    </div>
                </div>

                {{-- Form --}}
                <form action="{{ route('user.wizard.store') }}" method="POST" enctype="multipart/form-data"
                    id="upload-form" class="p-6">
                    @csrf
                    <input type="hidden" name="paket_id" value="{{ $paket->id }}">
                    <input type="hidden" name="step_number" value="{{ $step }}">

                    {{-- Dokumen Terupload --}}
                    @if ($submission && $submission->file_path)
                        <div
                            class="mb-6 p-4 bg-slate-50 rounded-xl border {{ $submission->status == 'ditolak' ? 'border-rose-200' : 'border-slate-200' }}">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 {{ $submission->status == 'ditolak' ? 'text-rose-600' : 'text-indigo-600' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-xs font-medium text-slate-600">
                                        {{ $submission->status == 'ditolak' ? 'Berkas Ditolak (Perlu Upload Ulang)' : 'Dokumen Terupload' }}
                                    </p>
                                </div>
                                <span
                                    class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $submission->status == 'ditolak' ? 'bg-rose-100 text-rose-700' : 'bg-indigo-100 text-indigo-700' }}">
                                    {{ $submission->status }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 gap-2">
                                @php $files = is_array($submission->file_path) ? $submission->file_path : [$submission->file_path]; @endphp
                                @foreach ($files as $file)
                                    <div
                                        class="flex items-center justify-between p-3 bg-white rounded-lg border border-slate-100">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2">
                                                    <path
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span
                                                class="text-sm font-medium text-slate-700 truncate">{{ basename($file) }}</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                                class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                                title="Buka Dokumen">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2">
                                                    <path
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                            <button type="button"
                                                onclick="deleteExistingFile('{{ $file }}', '{{ $submission->id }}')"
                                                class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                title="Hapus Dokumen">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2">
                                                    <path
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Upload Area --}}
                    <div
                        class="relative border-2 border-dashed rounded-xl p-8 text-center transition-colors {{ $errors->has('file_upload') ? 'border-rose-300 bg-rose-50/30' : 'border-slate-200 hover:border-indigo-400' }}">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-14 h-14 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 mb-3 group-hover:text-indigo-500 transition-colors border border-slate-200">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="1.5">
                                    <path
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p id="file-status-text" class="text-sm font-medium text-slate-700 mb-1">
                                {{ $submission && $submission->status == 'ditolak' ? 'Unggah Berkas Revisi' : ($step == 8 ? 'Klik untuk tambah berkas' : 'Pilih berkas') }}
                            </p>
                            <div id="file-name-container" class="hidden w-full max-w-sm">
                                <div id="file-list"
                                    class="text-xs text-slate-600 space-y-1 mb-2 bg-slate-50 p-3 rounded-lg"></div>
                                <button type="button" onclick="resetFileSelection(event)"
                                    class="text-xs font-medium text-rose-600 hover:text-rose-700 transition-colors">Hapus
                                    Pilihan</button>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">PDF, JPG, PNG (Maks. 2MB)</p>
                        </div>
                        <input type="file" id="file-input-field" name="file_upload{{ $step == 8 ? '[]' : '' }}"
                            {{ $step == 8 ? 'multiple' : '' }} onchange="updateFileDisplay()"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="flex justify-between mt-6 pt-6 border-t border-slate-100">
                        @if ($step > 1)
                            <a href="{{ route('user.step', [$paket->id, $step - 1]) }}"
                                class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Kembali
                            </a>
                        @else
                            <div></div>
                        @endif

                        <button type="submit" name="action" value="next"
                            class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                            <span>{{ $step == 11 ? 'Selesai & Kirim' : 'Simpan & Lanjut' }}</span>
                            @if ($step < 11)
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @endif
                        </button>
                    </div>
                </form>
            </div>

            {{-- Info Card --}}
            <div class="mt-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                <div class="flex items-start gap-3">
                    <svg class="w-4 h-4 text-slate-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <p class="text-xs text-slate-500">
                        <span class="font-medium text-slate-700">Informasi:</span> Jika dokumen ditolak, silakan unggah
                        ulang sesuai catatan revisi untuk melanjutkan proses verifikasi.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
