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
        /* Custom scrollbar untuk sidebar agar lebih rapi */
        nav::-webkit-scrollbar { width: 4px; }
        nav::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900">
    <div class="min-h-screen flex">
        
        <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6 border-b border-gray-50">
                <h1 class="text-xl font-bold text-blue-600 tracking-tight">E-HIBAH BBWS</h1>
                <p class="text-[10px] text-gray-400 uppercase font-bold mt-1">Sistem Administrasi Hibah</p>
            </div>
            
            <nav class="flex-1 mt-4 px-4 space-y-2 overflow-y-auto">
                <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('user.wizard') }}" 
                   class="flex items-center p-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') || request()->routeIs('user.wizard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="ml-3">Dashboard</span>
                </a>

                @if(Auth::user()->role == 'admin')
                <div x-data="{ openMain: true }" class="space-y-1">
                    <button @click="openMain = !openMain" class="w-full flex items-center justify-between p-3 text-gray-600 hover:bg-gray-50 rounded-xl transition font-bold text-xs uppercase tracking-wider">
                        <div class="flex items-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span class="ml-3">Daftar Paket</span>
                        </div>
                        <svg class="w-4 h-4 transform transition-transform" :class="openMain ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="openMain" x-cloak x-transition class="ml-2 space-y-1 border-l-2 border-gray-50">
                        {{-- Loop Nama Paket --}}
                        @forelse(\App\Models\Paket::with('steps')->get() as $paket)
                            <div x-data="{ openSteps: {{ (request()->is('admin/paket/'.$paket->id.'*') || (session('success') && $loop->last)) ? 'true' : 'false' }} }">
                                <button @click="openSteps = !openSteps" 
                                    class="w-full flex items-center justify-between pl-6 pr-3 py-2 text-sm transition {{ request()->is('admin/paket/'.$paket->id.'*') ? 'text-blue-600 font-bold' : 'text-gray-500 hover:text-blue-600' }}">
                                    <span class="truncate text-left">{{ $paket->nama_paket }}</span>
                                    <svg class="w-3 h-3 transform transition-transform" :class="openSteps ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                {{-- Loop 11 Steps --}}
                                <div x-show="openSteps" x-cloak x-transition class="ml-8 space-y-1 border-l border-gray-200">
                                    @foreach($paket->steps as $step)
                                        <a href="{{ route('admin.paket.users', ['paketId' => $paket->id, 'step' => $step->urutan]) }}" 
                                           class="block py-1.5 pl-4 text-[11px] transition {{ (request()->query('step') == $step->urutan && request()->is('admin/paket/'.$paket->id.'*')) ? 'text-blue-600 font-bold border-l-2 border-blue-600 -ml-[1px]' : 'text-gray-400 hover:text-blue-500' }}">
                                            Step {{ $step->urutan }}: {{ \Illuminate\Support\Str::limit($step->nama_step, 25) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <span class="block pl-6 py-2 text-xs text-gray-400 italic">Belum ada paket</span>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Manajemen</p>
                    <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-gray-50 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="ml-3 text-sm">User Akses</span>
                    </a>
                </div>
                @endif
            </nav>

            <div class="p-4 border-t border-gray-100">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-3 text-red-600 hover:bg-red-50 rounded-xl transition font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="ml-3">Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <header class="bg-white border-b border-gray-200 p-4 flex justify-between items-center px-8 flex-shrink-0">
                <div class="text-sm text-gray-500 italic">
                    {{ Auth::user()->nama_satker }}
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-800 leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold border-2 border-white shadow-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="p-8 overflow-y-auto bg-gray-50 flex-1">
                @if(session('success'))
                    <div class="max-w-4xl mx-auto mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm rounded shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content') 
            </main>
        </div>
    </div>
</body>
</html>