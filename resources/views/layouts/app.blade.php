<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-HIBAH BBWS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        nav::-webkit-scrollbar { width: 4px; }
        nav::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900">
    <div class="min-h-screen flex">
        
        {{-- SIDEBAR --}}
        <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6 border-b border-gray-50">
                <h1 class="text-xl font-bold text-blue-600 tracking-tight italic">E-HIBAH BBWS</h1>
                <p class="text-[10px] text-gray-400 uppercase font-bold mt-1 tracking-[0.2em]">Admin Desk Control</p>
            </div>
            
            <nav class="flex-1 mt-4 px-4 space-y-2 overflow-y-auto custom-scrollbar">
                {{-- Dashboard Link --}}
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center p-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="ml-3 text-sm">Dashboard</span>
                </a>

                @if(Auth::user()->role == 'admin')
                <div x-data="{ openMain: true }" class="pt-4">
                    <button @click="openMain = !openMain" class="w-full flex items-center justify-between px-3 mb-2 text-gray-400 hover:text-blue-600 transition font-black text-[10px] uppercase tracking-[0.2em]">
                        <span>Daftar Paket Kerja</span>
                        <svg class="w-3 h-3 transform transition-transform" :class="openMain ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3"></path></svg>
                    </button>
                    
                    <div x-show="openMain" x-transition class="space-y-4 mt-2">
                        @php
                            // Mengambil paket beserta user yang sudah mengupload (submissions) di paket tersebut
                            $pakets = \App\Models\Paket::with(['submissions.user'])->get();
                        @endphp

                        @foreach($pakets as $paket)
                        <div x-data="{ openUser: {{ request()->is('admin/paket/'.$paket->id.'*') ? 'true' : 'false' }} }" class="space-y-1">
                            {{-- Button Nama Paket --}}
                            <button @click="openUser = !openUser" 
                                class="w-full flex items-start gap-2 p-2 rounded-lg transition text-left {{ request()->is('admin/paket/'.$paket->id.'*') ? 'bg-blue-50/50' : '' }}">
                                <div class="w-1 h-4 bg-yellow-400 rounded-full mt-1"></div>
                                <span class="text-[11px] font-black text-blue-900 uppercase italic leading-tight flex-1">{{ $paket->nama_paket }}</span>
                            </button>

                            {{-- Daftar User di dalam Paket --}}
                            <div x-show="openUser" x-cloak x-transition class="ml-4 pl-3 border-l-2 border-gray-100 space-y-1">
                                @php
                                    // Grouping submission berdasarkan user agar user tidak duplikat di sidebar
                                    $uniqueUsers = $paket->submissions->unique('user_id');
                                @endphp

                                @forelse($uniqueUsers as $sub)
                                    @if($sub->user)
                                    <a href="{{ route('admin.paket.detail', [$paket->id, $sub->user_id]) }}" 
                                       class="group flex items-center justify-between py-1.5 pl-2 pr-1 rounded-md transition {{ (request()->segment(3) == $paket->id && request()->segment(5) == $sub->user_id) ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-600' }}">
                                        <div class="flex items-center gap-2 overflow-hidden">
                                            <div class="w-1.5 h-1.5 rounded-full {{ (request()->segment(3) == $paket->id && request()->segment(5) == $sub->user_id) ? 'bg-white' : 'bg-blue-400' }}"></div>
                                            <span class="text-[10px] font-bold uppercase truncate">{{ $sub->user->name }}</span>
                                        </div>
                                        <svg class="w-3 h-3 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"></path></svg>
                                    </a>
                                    @endif
                                @empty
                                    <span class="block py-1 pl-2 text-[9px] text-gray-300 italic uppercase">Belum ada pengunggah</span>
                                @endforelse
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 mt-4">
                    <p class="px-3 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Manajemen</p>
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center p-3 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="ml-3 text-sm">User Akses</span>
                    </a>
                </div>
                @endif
            </nav>

            <div class="p-4 border-t border-gray-100">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-3 text-red-600 hover:bg-red-50 rounded-xl transition font-black text-xs uppercase tracking-widest">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="ml-3">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- CONTENT AREA --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <header class="bg-white border-b border-gray-200 p-4 flex justify-between items-center px-8 flex-shrink-0">
                <div class="text-[10px] text-gray-400 font-black uppercase tracking-widest italic">
                    {{ Auth::user()->nama_satker ?? 'BALAI BESAR WILAYAH SUNGAI' }}
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs font-black text-blue-900 uppercase italic leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-gray-400 uppercase font-bold tracking-tighter">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-2xl flex items-center justify-center font-black shadow-lg shadow-blue-100">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="p-8 overflow-y-auto bg-gray-50 flex-1 custom-scrollbar">
                @yield('content') 
            </main>
        </div>
    </div>
</body>
</html>