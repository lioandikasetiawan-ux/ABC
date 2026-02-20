@extends('layouts.app')

@section('content')
<div x-data="{ openAdd: false, openEdit: false, currentUser: {} }" class="max-w-7xl mx-auto">
    
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900">User <span class="text-blue-600">Akses</span></h2>
            <p class="text-sm text-gray-500">Kelola kredensial dan hak akses aplikasi.</p>
        </div>
        <button @click="openAdd = true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition shadow-sm flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round"/></svg>
            Tambah User
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-100 text-green-600 rounded-xl text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama / Username</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Satker</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Role</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs mr-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->username }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium text-gray-500">{{ $user->nama_satker ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider {{ $user->role == 'admin' ? 'bg-purple-50 text-purple-600 border border-purple-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button @click="currentUser = {{ $user }}; openEdit = true" class="p-2 text-gray-400 hover:text-blue-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah --}}
    <div x-show="openAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity">
        <div @click.away="openAdd = false" class="bg-white w-full max-w-md rounded-[32px] p-8 shadow-2xl">
            <h3 class="text-xl font-black text-gray-900 mb-6 text-center uppercase tracking-tight">Daftarkan <span class="text-blue-600">User Baru</span></h3>
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 focus:ring-blue-500 text-sm font-bold">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Username</label>
                    <input type="text" name="username" required class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 focus:ring-blue-500 text-sm font-bold">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Role</label>
                        <select name="role" class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold">
                            <option value="user">USER</option>
                            <option value="admin">ADMIN</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Nama Satker</label>
                    <input type="text" name="nama_satker" placeholder="Contoh: Satker Wilayah I" class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openAdd = false" class="flex-1 py-3 text-sm font-bold text-gray-400">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-200">Simpan User</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div x-show="openEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div @click.away="openEdit = false" class="bg-white w-full max-w-md rounded-[32px] p-8 shadow-2xl">
            <h3 class="text-xl font-black text-gray-900 mb-6 text-center uppercase tracking-tight">Edit <span class="text-blue-600">Data User</span></h3>
            <form :action="`{{ url('admin/users') }}/${currentUser.id}`" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Nama Lengkap</label>
                    <input type="text" name="name" x-model="currentUser.name" required class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Username</label>
                    <input type="text" name="username" x-model="currentUser.username" required class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Role</label>
                        <select name="role" x-model="currentUser.role" class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold">
                            <option value="user">USER</option>
                            <option value="admin">ADMIN</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Ganti Password</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ganti" class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Nama Satker</label>
                    <input type="text" name="nama_satker" x-model="currentUser.nama_satker" class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openEdit = false" class="flex-1 py-3 text-sm font-bold text-gray-400">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-200">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection