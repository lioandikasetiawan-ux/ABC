<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-HIBAH BBWS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div class="login-card w-full max-w-md rounded-2xl shadow-2xl p-8 border border-white/20 relative">
        
        {{-- Navigasi Kembali --}}
        <div class="absolute top-6 left-6">
            <a href="{{ route('landing') }}" class="flex items-center gap-1 text-slate-400 hover:text-indigo-600 transition-colors text-xs font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>

        {{-- Logo & Header --}}
        <div class="text-center mb-8 mt-4">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-xl mb-4 shadow-lg shadow-indigo-200">
                <span class="text-2xl font-bold text-white">EH</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">E-HIBAH BBWS</h1>
            <p class="text-sm text-slate-500 mt-1">Masuk ke portal monitoring hibah</p>
        </div>

        {{-- Form Login --}}
        <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Username Field --}}
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5 ml-1">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <input type="text" name="username"
                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="Masukkan username" required>
                </div>
            </div>

            {{-- Password Field --}}
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5 ml-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <input type="password" name="password"
                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="Masukkan password" required>
                </div>
            </div>

            {{-- Error Message --}}
            @if ($errors->any())
                <div class="p-3 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <p class="text-xs font-medium text-rose-700">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3.5 rounded-xl text-sm transition-all shadow-lg shadow-indigo-200 hover:shadow-xl transform hover:-translate-y-0.5">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    MASUK
                </span>
            </button>
        </form>

        {{-- Footer --}}
        <p class="text-center text-xs text-slate-400 mt-6">
            © {{ date('Y') }} E-HIBAH BBWS. All rights reserved.
        </p>
    </div>
</body>

</html>