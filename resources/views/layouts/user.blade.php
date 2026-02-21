<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-HIBAH BBWS - User Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-slate-50 antialiased font-sans text-slate-800">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-72 bg-white border-r border-slate-100 flex flex-col sticky top-0 h-screen shadow-sm">
            {{-- Logo Area --}}
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-8 bg-indigo-600 rounded-full"></div>
                    <div>
                        <h1 class="text-xl font-bold text-indigo-600 tracking-tight">E-HIBAH BBWS</h1>
                        <p class="text-[10px] text-slate-400 font-medium uppercase mt-1 tracking-wider">User Dashboard
                        </p>
                    </div>
                </div>
            </div>

            {{-- User Profile Card --}}
            {{-- <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                    <div
                        class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-semibold text-lg shadow-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 font-medium">User</p>
                    </div>
                </div>
            </div> --}}

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto custom-scrollbar">
                {{-- Dashboard Link --}}
                <a href="{{ route('user.wizard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('user.wizard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                {{-- Main Menu Section --}}
                <div class="space-y-3">
                    <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu Utama</p>

                    {{-- Pantau Progres --}}
                    <a href="{{ route('user.progres.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('user.progres.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium">Pantau Progres</span>
                    </a>
                </div>
            </nav>

            {{-- Logout Button --}}
            <div class="p-4 border-t border-slate-100 mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        class="w-full flex items-center gap-3 px-4 py-3 text-rose-600 hover:bg-rose-50 rounded-xl transition-all font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-sm">Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content Area --}}
        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50">
            {{-- Header --}}
            <header class="bg-white border-b border-slate-100 px-8 py-4 flex-shrink-0 shadow-sm">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 bg-amber-400 rounded-full"></div>
                        <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">
                            {{ Auth::user()->nama_satker ?? 'Satker Wilayah' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 font-medium">User</p>
                        </div>
                        <div
                            class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-semibold shadow-md shadow-indigo-100">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar p-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>
