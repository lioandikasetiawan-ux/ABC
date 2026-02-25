<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-HIBAH BBWS</title>

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

<body class="bg-slate-50 font-sans antialiased text-slate-800">
    <div class="min-h-screen flex">

        {{-- SIDEBAR --}}
        <aside class="w-72 bg-white border-r border-slate-100 flex-shrink-0 hidden md:flex flex-col shadow-sm">
            {{-- Logo Area --}}
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-8 bg-indigo-600 rounded-full"></div>
                    <div>
                        <h1 class="text-xl font-bold text-indigo-600 tracking-tight">E-HIBAH BBWS</h1>
                        <p class="text-[10px] text-slate-400 font-medium uppercase mt-1 tracking-wider">Admin Desk
                            Control</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 mt-6 px-4 space-y-6 overflow-y-auto custom-scrollbar pb-6">
                {{-- Dashboard Link --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                @if (Auth::user()->role == 'admin')
                    {{-- Paket Kerja Section --}}
                    <div class="space-y-3">
                        <div class="px-4 py-2 text-slate-400">
                            <span class="text-xs font-semibold uppercase tracking-wider">Daftar Paket Kerja</span>
                        </div>

                        <div class="space-y-2">
                            @php
                                $pakets = \App\Models\Paket::with(['submissions.user'])->get();
                            @endphp

                            @foreach ($pakets as $paket)
                                @php
                                    // Karena 1 paket = 1 user, ambil submission pertama untuk mendapatkan user_id
                                    $submission = $paket->submissions->first();
                                    $isActive = request()->segment(3) == $paket->id;
                                @endphp

                                @if ($submission && $submission->user)
                                    <a href="{{ route('admin.paket.detail', [$paket->id, $submission->user_id]) }}"
                                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ $isActive ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                                        <div class="w-1 h-5 {{ $isActive ? 'bg-indigo-600' : 'bg-amber-400' }} rounded-full"></div>
                                        <span class="text-xs font-medium truncate flex-1">{{ $paket->nama_paket }}</span>
                                        <svg class="w-4 h-4 {{ $isActive ? 'text-indigo-600' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <div class="flex items-center gap-3 px-4 py-2.5 opacity-50 cursor-not-allowed">
                                        <div class="w-1 h-5 bg-slate-300 rounded-full"></div>
                                        <span class="text-xs font-medium text-slate-400 truncate">{{ $paket->nama_paket }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Manajemen Section --}}
                    <div class="pt-4 border-t border-slate-100">
                        <p class="px-4 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Manajemen</p>
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="text-sm font-medium">User Akses</span>
                        </a>
                        {{-- Menu Riwayat --}}
                        <a href="{{ route('admin.riwayat.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.riwayat.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-medium">Riwayat Aktivitas</span>
                        </a>
                    </div>
                @endif
            </nav>

            {{-- Logout Section --}}
            <div class="p-4 border-t border-slate-100 mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 text-rose-600 hover:bg-rose-50 rounded-xl transition-all font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-sm">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- CONTENT AREA --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50">
            {{-- Header --}}
            <header class="bg-white border-b border-slate-100 px-8 py-4 flex-shrink-0 shadow-sm">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 bg-amber-400 rounded-full"></div>
                        <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">
                            {{ Auth::user()->nama_satker ?? 'BALAI BESAR WILAYAH SUNGAI' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 font-medium">{{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                        <div
                            class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-semibold shadow-md shadow-indigo-100">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 overflow-y-auto custom-scrollbar p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>