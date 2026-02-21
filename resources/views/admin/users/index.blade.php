@extends('layouts.app')

@section('content')
    <div x-data="{ openAdd: false, openEdit: false, currentUser: {} }" class="w-full px-6 py-4">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-1 h-8 bg-indigo-600 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-slate-800">User Akses</h2>
                </div>
                <p class="text-sm text-slate-500 ml-4">Kelola kredensial dan hak akses aplikasi</p>
            </div>

            <button @click="openAdd = true"
                class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium text-sm transition-all shadow-lg shadow-indigo-200 hover:shadow-xl flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path d="M12 4v16m8-8H4" stroke-linecap="round" />
                </svg>
                Tambah User
            </button>
        </div>

        {{-- Alert Success --}}
        @if (session('success'))
            <div
                class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm font-medium rounded-r-xl shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500">Nama / Username</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500">Satker</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 text-center">Role</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($users as $user)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-semibold text-sm">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $user->username }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-slate-600">{{ $user->nama_satker ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg
                                {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            @if ($user->role == 'admin')
                                                <path
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            @else
                                                <path
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            @endif
                                        </svg>
                                        {{ strtoupper($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <button @click="currentUser = {{ $user }}; openEdit = true"
                                            class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-colors"
                                            title="Edit User">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                stroke-width="2">
                                                <path
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>

                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus user ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100 transition-colors"
                                                title="Hapus User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2">
                                                    <path
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Tambah --}}
        <div x-show="openAdd" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">

            <div @click.away="openAdd = false" class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 opacity-0"
                x-transition:enter-end="scale-100 opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0">

                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1 h-8 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-lg font-semibold text-slate-800">Tambah User Baru</h3>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Username</label>
                        <input type="text" name="username" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Masukkan username">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Role</label>
                            <select name="role"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="user">USER</option>
                                <option value="admin">ADMIN</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Password</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Nama Satker</label>
                        <input type="text" name="nama_satker"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Contoh: Satker Wilayah I">
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="openAdd = false"
                            class="flex-1 px-4 py-3 text-sm font-medium text-slate-600 hover:text-slate-800 rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                            Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit --}}
        <div x-show="openEdit" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">

            <div @click.away="openEdit = false" class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-95 opacity-0"
                x-transition:enter-end="scale-100 opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0">

                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1 h-8 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-lg font-semibold text-slate-800">Edit User</h3>
                </div>

                <form :action="`{{ url('admin/users') }}/${currentUser.id}`" method="POST" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="currentUser.name" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Username</label>
                        <input type="text" name="username" x-model="currentUser.username" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Role</label>
                            <select name="role" x-model="currentUser.role"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="user">USER</option>
                                <option value="admin">ADMIN</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Ganti Password</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Kosongkan jika tidak ganti">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Nama Satker</label>
                        <input type="text" name="nama_satker" x-model="currentUser.nama_satker"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="openEdit = false"
                            class="flex-1 px-4 py-3 text-sm font-medium text-slate-600 hover:text-slate-800 rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                            Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection
