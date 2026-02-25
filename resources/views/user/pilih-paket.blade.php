@extends('layouts.user')

@section('content')
    <div class="w-full px-6 py-8">
        {{-- Header --}}
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-1 h-8 bg-indigo-600 rounded-full"></div>
                <h2 class="text-2xl font-bold text-slate-800">
                    Mulai Pelaporan <span class="text-indigo-600">Hibah</span>
                </h2>
            </div>
            <p class="text-sm text-slate-500 ml-4">Pilih paket pekerjaan di bawah ini untuk mengelola dokumen</p>
        </div>

        {{-- Main Card --}}
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-indigo-500 to-indigo-600"></div>

                <div class="p-8">
                    <div class="flex justify-center mb-8">
                        <div class="w-20 h-20 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>

                    <form x-data="{ paketId: '' }" :action="'/wizard/paket/' + paketId + '/step/1'" method="GET" class="space-y-6">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-2 text-center">
                                Pilih Paket Pekerjaan
                            </label>

                            <div class="relative">
                                <select x-model="paketId" required
                                    class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Paket Pekerjaan --</option>
                                    @foreach ($pakets as $paket)
                                        @php
                                            $isLocked = $paket->step_verifikasi >= 11;
                                            $isTaken = $paket->is_taken_by_other; // Logika baru: Sudah diisi user lain
                                            $progress = round($paket->progres_persen ?? 0);
                                        @endphp
                                        <option value="{{ $paket->id }}" {{ ($isLocked || $isTaken) ? 'disabled' : '' }}
                                            class="{{ ($isLocked || $isTaken) ? 'text-slate-400' : 'text-slate-700' }}">
                                            @if ($isTaken)
                                                {{ $paket->nama_paket }} (Sudah Digunakan User Lain)
                                            @elseif ($isLocked)
                                                {{ $paket->nama_paket }} (Selesai / Terkunci)
                                            @else
                                                {{ $paket->nama_paket }} [{{ $progress }}%]
                                            @endif
                                        </option>
                                    @endforeach
                                </select>

                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <button type="submit" :disabled="!paketId"
                            class="w-full py-4 bg-indigo-600 text-white rounded-xl font-medium shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none flex items-center justify-center gap-2">
                            <span>Buka Detail Paket</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        {{-- Pesan Peringatan --}}
                        <div x-show="paketId && $el.closest('form').querySelector('option:checked').disabled" x-cloak
                            class="mt-4 p-3 bg-amber-50 border-l-4 border-amber-500 rounded-r-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="text-xs font-medium text-amber-700">
                                    Paket ini tidak dapat dipilih karena sudah dikerjakan user lain atau sudah terkunci.
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
@endsection