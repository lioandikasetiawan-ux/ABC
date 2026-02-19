<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-HIBAH BBWS - User Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 antialiased font-sans">
    <div class="flex min-h-screen">
        <aside class="w-72 bg-white border-r border-gray-100 flex flex-col sticky top-0 h-screen">
            <div class="p-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-blue-200">B</div>
                    <div>
                        <h1 class="text-sm font-black text-gray-800 uppercase tracking-tighter">E-HIBAH BBWS</h1>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Sistem Administrasi</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-6 space-y-1 overflow-y-auto pb-8">
                <a href="/dashboard" class="flex items-center px-4 py-3 text-blue-600 bg-blue-50 rounded-2xl font-bold text-sm transition-all mb-6">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>

                <div class="py-2">
                    <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Ringkasan Paket</p>
                    
                    @foreach($pakets as $p)
                    <div class="px-4 py-4 bg-white border border-gray-100 rounded-2xl mb-3 hover:border-blue-200 transition-all group shadow-sm">
                        <p class="text-[11px] font-bold text-gray-700 truncate mb-3 group-hover:text-blue-600 transition-colors">
                            {{ $p->nama_paket }}
                        </p>
                        
                        <div class="w-full bg-gray-100 rounded-full h-1 mb-3 overflow-hidden">
                            <div class="bg-blue-600 h-1 rounded-full transition-all duration-700" 
                                 style="width: {{ $p->progres_persen ?? 0 }}%"></div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] font-black text-blue-600">{{ round($p->progres_persen ?? 0) }}%</span>
                                <span class="text-[8px] text-gray-400 font-bold uppercase tracking-tighter">{{ $p->step_selesai ?? 0 }}/11 Step</span>
                            </div>

                            @if($p->status_admin == 'approved')
                                <div class="flex items-center text-green-600 bg-green-50 px-2 py-1 rounded-lg border border-green-100" title="Disetujui">
                                    <span class="text-[8px] font-black uppercase">Selesai</span>
                                </div>
                            @elseif($p->status_admin == 'rejected')
                                <a href="#" class="flex items-center text-red-600 bg-red-50 px-2 py-1 rounded-lg border border-red-100 animate-pulse" title="Klik untuk lihat revisi">
                                    <span class="text-[8px] font-black uppercase">Revisi</span>
                                </a>
                            @else
                                <div class="flex items-center text-amber-600 bg-amber-50 px-2 py-1 rounded-lg border border-amber-100" title="Menunggu Verifikasi">
                                    <span class="text-[8px] font-black uppercase">Pending</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </nav>

            <div class="p-6 mt-auto border-t border-gray-50">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="flex items-center w-full px-5 py-3 text-red-500 bg-red-50 hover:bg-red-100 rounded-2xl transition font-black text-xs uppercase tracking-widest">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Keluar Sistem
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-x-hidden">
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-30 px-10 flex items-center justify-between">
                <span class="text-sm font-medium text-gray-400 italic">Satker Wilayah Cirebon</span>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-blue-600 font-medium">User Akses</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-yellow-400 border-2 border-white shadow-sm flex items-center justify-center font-bold text-blue-900 uppercase">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <div class="p-10">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>