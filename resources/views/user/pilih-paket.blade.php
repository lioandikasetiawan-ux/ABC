@extends('layouts.user') {{-- Menggunakan layout user yang baru --}}

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-10">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            Pilih <span class="text-blue-600">Paket Hibah</span>
        </h2>
        <p class="text-gray-500 mt-2">Silakan pilih paket pekerjaan untuk memulai proses pelaporan hibah.</p>
        <div class="w-20 h-1.5 bg-yellow-400 mt-4 rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($pakets as $paket)
        <div class="group relative bg-white rounded-3xl border border-gray-100 shadow-xl shadow-blue-900/5 overflow-hidden hover:shadow-2xl hover:shadow-blue-900/10 transition-all duration-300 transform hover:-translate-y-2">
            
            <div class="absolute top-0 left-0 w-full h-1.5 bg-yellow-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>

            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-3 bg-blue-50 rounded-2xl group-hover:bg-yellow-50 transition-colors duration-300">
                        <svg class="w-8 h-8 text-blue-600 group-hover:text-yellow-600 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    
                    {{-- Badge Status Berdasarkan Progres --}}
                    @if(($paket->progres_persen ?? 0) >= 100)
                        <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 bg-green-100 text-green-600 rounded-full italic">Selesai</span>
                    @elseif(($paket->progres_persen ?? 0) > 0)
                        <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 bg-blue-100 text-blue-600 rounded-full italic">Progres</span>
                    @else
                        <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 bg-gray-100 text-gray-500 rounded-full italic">Belum Mulai</span>
                    @endif
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6 leading-tight min-h-[3.5rem] group-hover:text-blue-600 transition-colors">
                    {{ $paket->nama_paket }}
                </h3>

                <div class="mb-8">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Progres Laporan</span>
                        <span class="text-sm font-black text-blue-600">{{ round($paket->progres_persen ?? 0) }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden border border-gray-50">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-700 h-full rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(37,99,235,0.3)]"
                             style="width: {{ $paket->progres_persen ?? 0 }}%">
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 font-medium italic">
                        * Terunggah {{ $paket->step_selesai ?? 0 }} dari 11 dokumen mandatori
                    </p>
                </div>

                <a href="{{ route('user.step', [$paket->id, 1]) }}" 
                   class="relative flex items-center justify-center w-full py-4 bg-blue-600 text-white rounded-2xl font-bold overflow-hidden transition-all duration-300 group-hover:bg-blue-700 shadow-lg shadow-blue-200">
                    <span class="relative z-10 flex items-center">
                        {{ ($paket->progres_persen ?? 0) > 0 ? 'Lanjutkan Upload' : 'Mulai Upload' }}
                        <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </span>
                    <div class="absolute inset-0 w-1/2 h-full bg-white/10 skew-x-[-25deg] -translate-x-full group-hover:translate-x-[250%] transition-transform duration-1000"></div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection