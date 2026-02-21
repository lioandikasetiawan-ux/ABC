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
                    <div x-data="{ openMain: true }" class="space-y-3">
                        <button @click="openMain = !openMain"
                            class="w-full flex items-center justify-between px-4 py-2 text-slate-400 hover:text-indigo-600 transition-colors">
                            <span class="text-xs font-semibold uppercase tracking-wider">Daftar Paket Kerja</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="openMain ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="openMain" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" class="space-y-2">
                            @php
                                $pakets = \App\Models\Paket::with(['submissions.user'])->get();
                            @endphp

                            @foreach ($pakets as $paket)
                                <div x-data="{ openUser: {{ request()->is('admin/paket/' . $paket->id . '*') ? 'true' : 'false' }} }" class="space-y-1">
                                    {{-- Button Nama Paket --}}
                                    <button @click="openUser = !openUser"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->is('admin/paket/' . $paket->id . '*') ? 'bg-indigo-50' : 'hover:bg-slate-50' }}">
                                        <div class="w-1 h-5 bg-amber-400 rounded-full"></div>
                                        <span
                                            class="text-sm font-medium text-slate-700 flex-1 text-left">{{ $paket->nama_paket }}</span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                            :class="openUser ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    {{-- Daftar User --}}
                                    <div x-show="openUser" x-cloak x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        class="ml-7 pl-4 border-l-2 border-slate-100 space-y-1">
                                        @php
                                            $uniqueUsers = $paket->submissions->unique('user_id');
                                        @endphp

                                        @forelse($uniqueUsers as $sub)
                                            @if ($sub->user)
                                                <a href="{{ route('admin.paket.detail', [$paket->id, $sub->user_id]) }}"
                                                    class="group flex items-center justify-between py-2 px-3 rounded-lg transition-all {{ request()->segment(3) == $paket->id && request()->segment(5) == $sub->user_id ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                                                    <div class="flex items-center gap-2 overflow-hidden">
                                                        <div
                                                            class="w-1.5 h-1.5 rounded-full {{ request()->segment(3) == $paket->id && request()->segment(5) == $sub->user_id ? 'bg-white' : 'bg-indigo-400' }}">
                                                        </div>
                                                        <span
                                                            class="text-xs font-medium truncate">{{ $sub->user->name }}</span>
                                                    </div>
                                                    <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity {{ request()->segment(3) == $paket->id && request()->segment(5) == $sub->user_id ? 'opacity-100' : '' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        stroke-width="2">
                                                        <path d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            @endif
                                        @empty
                                            <span class="block py-2 px-3 text-xs text-slate-400 italic">Belum ada
                                                pengunggah</span>
                                        @endforelse
                                    </div>
                                </div>
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
