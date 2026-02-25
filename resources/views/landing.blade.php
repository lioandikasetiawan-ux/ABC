<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Hibah BBWS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: radial-gradient(circle at top right, #f8fafc, #eff6ff); 
            min-height: 100vh;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .step-line {
            background: linear-gradient(90deg, #e2e8f0 0%, #e2e8f0 100%);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-in { animation: slideIn 0.5s ease-out forwards; }
    </style>
</head>
<body class="antialiased text-slate-900">

   {{-- Navbar --}}
    <nav class="bg-white/70 backdrop-blur-xl sticky top-0 z-50 border-b border-slate-200/60 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-violet-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-extrabold text-slate-900 tracking-tight leading-none text-lg">E-HIBAH</span>
                    <span class="text-[10px] font-bold text-indigo-600 tracking-[0.2em] uppercase">BBWS Portal</span>
                </div>
            </div>

            {{-- Tombol LOGIN Baru --}}
            <a href="{{ route('login') }}" 
               class="relative inline-flex items-center justify-center px-8 py-2.5 font-bold text-white transition-all duration-300 bg-gradient-to-r from-indigo-600 to-violet-600 rounded-full group shadow-lg shadow-indigo-200 hover:shadow-indigo-400 hover:scale-105 active:scale-95">
                <span class="relative text-xs uppercase tracking-widest">LOGIN</span>
                {{-- Efek kilauan saat hover --}}
                <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-white/0 via-white/20 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
            </a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-16">
        {{-- Header Section --}}
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-2">
                <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-full">Live Monitoring</span>
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Pantau Progres <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-500">Hibah</span></h1>
                <p class="text-slate-500 font-medium max-w-lg">Transparansi pengajuan dokumen hibah secara real-time berdasarkan unit kerja.</p>
            </div>
            <div class="flex gap-2">
                <div class="px-4 py-2 bg-white border border-slate-200 rounded-2xl shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Total Paket</p>
                    <p class="text-xl font-black text-slate-800">{{ count($monitoringData) }}</p>
                </div>
            </div>
        </div>

        {{-- Cards Grid --}}
        <div class="grid gap-8">
            @forelse($monitoringData as $data)
            <div class="glass-card rounded-[2.5rem] border border-slate-200/60 shadow-xl shadow-slate-200/40 p-8 md:p-10 transition-all hover:shadow-2xl hover:shadow-indigo-100 hover:-translate-y-1 animate-slide-in">
                
                {{-- Card Header --}}
                <div class="flex flex-col md:flex-row md:items-start justify-between mb-12 gap-6">
                    <div class="space-y-3">
                        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight leading-tight group-hover:text-indigo-600 transition-colors">{{ $data->nama_paket }}</h2>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-100 rounded-full shadow-sm">
                            <div class="w-6 h-6 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-[10px] text-white font-bold">
                                {{ substr($data->user_name, 0, 1) }}
                            </div>
                            <span class="text-slate-600 font-bold text-xs uppercase tracking-wider">{{ $data->user_name }}</span>
                        </div>
                    </div>
                    
                    <div class="relative flex-shrink-0">
                        <div class="flex flex-col items-center justify-center w-24 h-24 rounded-3xl bg-indigo-600 shadow-xl shadow-indigo-200 text-white">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Progress</span>
                            <div class="flex items-baseline gap-0.5">
                                <span class="text-3xl font-black">{{ $data->total_verified }}</span>
                                <span class="text-indigo-300 text-sm font-bold">/11</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Progress Tracker --}}
                <div class="overflow-x-auto no-scrollbar pb-4">
                    <div class="flex items-start min-w-[1100px] justify-between relative px-4">
                        {{-- Background Line --}}
                        <div class="absolute top-6 left-0 w-full h-[3px] bg-slate-100 -z-0"></div>
                        
                        @foreach($data->visual_steps as $vs)
                            @php
                                $statusClasses = [
                                    'verified' => 'bg-emerald-500 text-white shadow-lg shadow-emerald-200 ring-4 ring-emerald-50',
                                    'rejected' => 'bg-rose-500 text-white shadow-lg shadow-rose-200 ring-4 ring-rose-50',
                                    'completed' => 'bg-amber-400 text-slate-900 shadow-lg shadow-amber-100 ring-4 ring-amber-50',
                                    'lock' => 'bg-white text-slate-300 border-2 border-slate-200'
                                ];
                                $currentStatus = $statusClasses[$vs['status']] ?? $statusClasses['lock'];
                            @endphp

                            <div class="flex flex-col items-center flex-1 z-10 group/step">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-sm transition-all duration-500 transform group-hover/step:scale-110 {{ $currentStatus }}">
                                    @if($vs['status'] == 'verified')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @elseif($vs['status'] == 'rejected')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    @else
                                        {{ $vs['nomor'] }}
                                    @endif
                                </div>
                                <div class="mt-5 px-2">
                                    <span class="block text-[10px] font-bold text-center uppercase leading-snug transition-colors duration-300 w-24 
                                        {{ in_array($vs['status'], ['verified', 'completed']) ? 'text-slate-800' : 'text-slate-400' }}">
                                        {{ $vs['label'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white/50 backdrop-blur rounded-[3rem] p-24 text-center border-2 border-dashed border-slate-200">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-sm">Belum Ada Data Pengajuan</p>
            </div>
            @endforelse
        </div>
    </main>

    {{-- Legend Float --}}
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 bg-slate-900/90 backdrop-blur-xl shadow-2xl px-8 py-4 rounded-3xl flex gap-10 z-50 border border-white/10">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
            <span class="text-[10px] font-black uppercase text-white tracking-widest">Terverifikasi</span>
        </div>
        <div class="flex items-center gap-3 border-x border-white/10 px-10">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shadow-[0_0_10px_rgba(251,191,36,0.5)]"></span>
            <span class="text-[10px] font-black uppercase text-white tracking-widest">Review</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.5)]"></span>
            <span class="text-[10px] font-black uppercase text-white tracking-widest">Revisi</span>
        </div>
    </div>

</body>
</html>