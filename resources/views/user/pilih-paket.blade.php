@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            Mulai Pelaporan <span class="text-blue-600">Hibah</span>
        </h2>
        <p class="text-gray-500 mt-2">Pilih paket pekerjaan di bawah ini untuk mengelola dokumen.</p>
    </div>

    <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-2xl shadow-blue-900/5 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-blue-700"></div>
        
        <div class="flex flex-col items-center">
            <div class="w-20 h-20 bg-blue-50 rounded-3xl flex items-center justify-center text-blue-600 mb-8">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>

            <div class="w-full max-w-md">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3 text-center">
                    Pilih Daftar Paket Anda
                </label>
                
                <form x-data="{ paketId: '' }" :action="'/wizard/paket/' + paketId + '/step/1'" method="GET">
                    <div class="relative">
                        <select x-model="paketId" required
                            class="w-full bg-slate-50 border-2 border-gray-100 rounded-2xl px-6 py-4 text-gray-700 font-bold appearance-none focus:border-blue-500 focus:ring-0 transition-all cursor-pointer">
                            <option value="" disabled selected>-- Cari & Pilih Paket Pekerjaan --</option>
                            @foreach($pakets as $paket)
                                @php $isLocked = $paket->step_verifikasi >= 11; @endphp
                                <option value="{{ $paket->id }}" 
                                    {{ $isLocked ? 'disabled' : '' }}
                                    class="{{ $isLocked ? 'text-gray-400 opacity-50' : 'text-gray-700' }}">
                                    @if($isLocked)
                                        {{ $paket->nama_paket }} (Selesai/Terkunci)
                                    @else
                                        [{{ round($paket->progres_persen ?? 0) }}%] {{ $paket->nama_paket }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>

                    <button type="submit" :disabled="!paketId"
                        class="w-full mt-6 py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all disabled:opacity-50 disabled:bg-gray-300 disabled:shadow-none flex items-center justify-center gap-2">
                        Buka Detail Paket
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    
                    <p x-cloak x-show="paketId && $el.closest('form').querySelector('option:checked').disabled" 
                       class="mt-4 text-center text-xs text-gray-400 font-medium italic">
                        * Paket ini telah diverifikasi penuh dan dikunci oleh sistem.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection