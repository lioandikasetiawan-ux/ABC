@extends('layouts.user')

@section('content')
    <div class="w-full px-6 py-4">
        {{-- Progress Stepper --}}
        <div class="mb-12 relative">
            {{-- Progress Line --}}
            <div class="absolute top-5 left-0 w-full h-0.5 bg-slate-200 -z-10"></div>

            {{-- Steps --}}
            <div class="flex justify-between">
                @for ($i = 1; $i <= 11; $i++)
                    <div class="flex flex-col items-center">
                        {{-- Step Circle --}}
                        <div
                            class="w-10 h-10 rounded-xl flex items-center justify-center font-semibold text-sm transition-all duration-300
                        {{ $step == $i
                            ? 'bg-indigo-600 text-white ring-4 ring-indigo-100'
                            : ($step > $i
                                ? 'bg-emerald-500 text-white'
                                : 'bg-white border-2 border-slate-200 text-slate-400') }}">
                            @if ($step > $i)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2.5">
                                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @else
                                {{ $i }}
                            @endif
                        </div>

                        {{-- Step Label --}}
                        <span class="text-[10px] font-medium mt-2 {{ $step == $i ? 'text-indigo-600' : 'text-slate-400' }}">
                            Step {{ $i }}
                        </span>
                    </div>
                @endfor
            </div>
        </div>

        {{-- Main Card --}}
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                {{-- Header --}}
                <div
                    class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-1 h-5 bg-indigo-600 rounded-full"></div>
                            <h2 class="text-lg font-semibold text-slate-800">Langkah ke-{{ $step }}</h2>
                        </div>
                        <p class="text-sm text-slate-500 ml-3">{{ $paket->nama_paket }}</p>
                    </div>

                    {{-- Progress Badge --}}
                    <div class="flex items-center gap-3">
                        <div class="w-32 h-2 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-600 rounded-full" style="width: {{ round(($step / 11) * 100) }}%">
                            </div>
                        </div>
                        <span class="text-xs font-medium text-indigo-600">{{ round(($step / 11) * 100) }}%</span>
                    </div>
                </div>

                {{-- Form --}}
                <form action="{{ route('user.wizard.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <input type="hidden" name="paket_id" value="{{ $paket->id }}">
                    <input type="hidden" name="step_number" value="{{ $step }}">

                    <div class="space-y-6">
                        {{-- Upload Area --}}
                        <div
                            class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-indigo-400 transition-colors group">
                            @if ($submission && $submission->file_path)
                                {{-- Existing File Info --}}
                                <div
                                    class="mb-6 inline-flex items-center gap-3 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm font-medium text-emerald-700">File sudah diunggah</p>
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank"
                                            class="text-xs text-emerald-600 hover:text-emerald-700 underline inline-flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                stroke-width="2">
                                                <path
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            Lihat Dokumen
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Upload Interface --}}
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-16 h-16 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 mb-4 transition-colors border border-slate-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="1.5">
                                        <path
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>

                                <p class="text-sm font-medium text-slate-700">Pilih file untuk diunggah</p>
                                <p class="text-xs text-slate-400 mt-1">Format: PDF, JPG, PNG (Maks. 2MB)</p>

                                <div class="mt-4 w-full max-w-sm">
                                    <input type="file" name="file_upload"
                                        class="block w-full text-sm text-slate-500
                                              file:mr-4 file:py-2.5 file:px-6
                                              file:rounded-lg file:border-0
                                              file:text-sm file:font-medium
                                              file:bg-indigo-600 file:text-white
                                              hover:file:bg-indigo-700
                                              file:cursor-pointer file:transition-colors
                                              file:shadow-sm file:shadow-indigo-200
                                              cursor-pointer">
                                </div>
                            </div>
                        </div>

                        {{-- Status Info --}}
                        @if ($submission)
                            <div
                                class="p-4 rounded-xl {{ $submission->status == 'ditolak' ? 'bg-rose-50 border border-rose-100' : 'bg-blue-50 border border-blue-100' }}">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-6 h-6 rounded-lg {{ $submission->status == 'ditolak' ? 'bg-rose-100' : 'bg-blue-100' }} flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 {{ $submission->status == 'ditolak' ? 'text-rose-600' : 'text-blue-600' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            @if ($submission->status == 'ditolak')
                                                <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            @else
                                                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span
                                            class="text-xs font-medium {{ $submission->status == 'ditolak' ? 'text-rose-600' : 'text-blue-600' }} uppercase tracking-wider">
                                            {{ $submission->status == 'ditolak' ? 'Ditolak' : 'Menunggu Verifikasi' }}
                                        </span>
                                        @if ($submission->catatan_admin)
                                            <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                                                "{{ $submission->catatan_admin }}"
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Help Text for Rejected Files --}}
                        @if ($submission && $submission->status == 'ditolak')
                            <div class="p-3 bg-amber-50 rounded-lg border-l-4 border-amber-500">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-xs text-amber-700">Silakan unggah ulang dokumen sesuai catatan revisi di
                                        atas</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="flex justify-between mt-8 pt-6 border-t border-slate-100">
                        @if ($step > 1)
                            <a href="{{ route('user.step', [$paket->id, $step - 1]) }}"
                                class="inline-flex items-center px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition-all hover:border-slate-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Sebelumnya
                            </a>
                        @else
                            <div></div>
                        @endif

                        <div class="flex gap-3">
                            <button type="submit" name="action" value="save"
                                class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition-all hover:border-slate-300">
                                Simpan Draft
                            </button>

                            <button type="submit" name="action" value="next"
                                class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 hover:shadow-xl">
                                <span>{{ $step == 11 ? 'Selesai & Kirim' : 'Simpan & Lanjut' }}</span>
                                @if ($step < 11)
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2">
                                        <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @endif
                            </button>
                        </div>
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
                        <span class="font-medium text-slate-700">Informasi:</span> Setiap langkah hanya dapat mengunggah
                        satu file.
                        Jika dokumen ditolak, silakan unggah ulang sesuai catatan revisi.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
