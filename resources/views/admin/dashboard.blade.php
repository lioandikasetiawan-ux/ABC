@extends('layouts.app')

@section('content')
    <div class="w-full px-6 py-2" x-data="{ openModal: false, openManage: false }">

        {{-- NOTIFIKASI --}}
        <div x-data="{
            show: false,
            message: '',
            type: 'success',
            init() { @if (session('success')) this.showNotification('{{ session('success') }}', 'success'); @endif },
            showNotification(msg, type) {
                this.message = msg;
                this.type = type;
                this.show = true;
                setTimeout(() => { this.show = false }, 3000);
            }
        }" x-show="show" class="fixed top-6 left-1/2 -translate-x-1/2 z-[100]" x-cloak>
            <div :class="type === 'success' ? 'border-emerald-500 bg-emerald-50' : 'border-rose-500 bg-rose-50'"
                class="flex items-center gap-3 px-6 py-4 border-l-4 rounded-2xl shadow-lg min-w-[320px] backdrop-blur-sm">
                <div :class="type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'" class="w-1 h-8 rounded-full"></div>
                <p class="text-xs font-semibold text-slate-700" x-text="message"></p>
            </div>
        </div>

        {{-- HEADER --}}
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-1 h-8 bg-indigo-600 rounded-full"></div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Monitoring Progres Hibah</h2>
                        <p class="text-slate-500 text-xs font-medium mt-0.5">Admin Verification Desk</p>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <button @click="openManage = true"
                    class="flex-1 sm:flex-none bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-50 transition-all shadow-sm hover:shadow">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        Kelola Paket
                    </span>
                </button>
                <button @click="openModal = true"
                    class="flex-1 sm:flex-none bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 hover:shadow-xl">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Paket
                    </span>
                </button>
            </div>
        </div>

        {{-- TABEL MONITORING --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500">Nama Paket Pekerjaan</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500">PIC / Pengunggah</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 text-center">Progres Upload</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 text-center">Progres Verifikasi</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($pakets as $paket)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-800">{{ $paket->nama_paket }}</span>
                                        
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span
                                                class="text-xs font-medium text-indigo-600">{{ substr($paket->user_name ?? '?', 0, 1) }}</span>
                                        </div>
                                        <span
                                            class="text-sm text-slate-600">{{ $paket->user_name ?? 'Belum ditugaskan' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center gap-3">
                                        <span
                                            class="text-sm font-semibold {{ $paket->uploaded_count == 11 ? 'text-emerald-600' : 'text-amber-600' }}">
                                            {{ $paket->uploaded_count }}/11
                                        </span>
                                        <div class="w-16 h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $paket->uploaded_count == 11 ? 'bg-emerald-500' : 'bg-amber-500' }}"
                                                style="width: {{ ($paket->uploaded_count / 11) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <span
                                            class="text-sm font-semibold {{ $paket->verified_count == $paket->uploaded_count && $paket->uploaded_count > 0 ? 'text-emerald-600' : 'text-slate-600' }}">
                                            {{ $paket->verified_count }}/{{ $paket->uploaded_count ?: '0' }}
                                        </span>
                                        @if ($paket->uploaded_count > 0)
                                            <span class="text-xs text-slate-400">
                                                ({{ round(($paket->verified_count / $paket->uploaded_count) * 100) }}%)
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $status = [
                                            'SELESAI' => ['bg-emerald-100 text-emerald-700', 'CheckCircle'],
                                            'PERLU VERIF' => ['bg-amber-100 text-amber-700', 'AlertCircle'],
                                            'ON PROGRESS' => ['bg-blue-100 text-blue-700', 'Clock'],
                                            'DRAFT' => ['bg-slate-100 text-slate-600', 'FileText'],
                                        ][$paket->status_label] ?? ['bg-slate-100 text-slate-600', 'File'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $status[0] }} text-xs font-medium rounded-lg">
                                        @switch($status[1])
                                            @case('CheckCircle')
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @break

                                            @case('AlertCircle')
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @break

                                            @case('Clock')
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @break

                                            @default
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2-10H7a2 2 0 00-2 2v14l4-4h10a2 2 0 002-2V6a2 2 0 00-2-2z" />
                                                </svg>
                                        @endswitch
                                        {{ $paket->status_label }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    @if ($paket->user_id)
                                        <a href="{{ route('admin.paket.detail', [$paket->id, $paket->user_id]) }}"
                                            class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-medium text-sm group">
                                            Detail
                                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-sm text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2-10H7a2 2 0 00-2 2v14l4-4h10a2 2 0 002-2V6a2 2 0 00-2-2z" />
                                            </svg>
                                            <p class="text-slate-400 font-medium">Belum ada data paket</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MODAL KELOLA DATABASE --}}
            <div x-show="openManage" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[80vh] overflow-hidden flex flex-col"
                    @click.away="openManage = false">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-slate-800">Kelola Database Paket</h3>
                        <button @click="openManage = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto space-y-3">
                        @foreach ($pakets as $p)
                            <div x-data="{ editing: false, newName: '{{ $p->nama_paket }}' }"
                                class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors group">
                                <div class="flex-1 mr-3">
                                    <template x-if="!editing">
                                        <span class="font-medium text-slate-800">{{ $p->nama_paket }}</span>
                                    </template>
                                    <template x-if="editing">
                                        <form action="{{ route('admin.paket.update', $p->id) }}" method="POST"
                                            class="flex gap-2">
                                            @csrf @method('PUT')
                                            <input type="text" name="nama_paket" x-model="newName"
                                                class="flex-1 px-3 py-2 bg-white rounded-lg text-sm border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                            <button type="submit"
                                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg text-xs font-medium hover:bg-indigo-700 transition-colors">Simpan</button>
                                        </form>
                                    </template>
                                </div>
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                    x-show="!editing">
                                    <button @click="editing = true"
                                        class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-white rounded-lg transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.paket.destroy', $p->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus paket ini? Semua data terkait akan ikut terhapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-slate-500 hover:text-rose-600 hover:bg-white rounded-lg transition-colors"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- MODAL TAMBAH PAKET --}}
            <div x-show="openModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
                <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-md" @click.away="openModal = false">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-8 bg-indigo-600 rounded-full"></div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800">Tambah Paket Baru</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Akan membuat 11 tahapan input secara otomatis</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.paket.store') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-xs font-medium text-slate-600 mb-2">Nama Paket Pekerjaan</label>
                            <input type="text" name="nama_paket"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                placeholder="Contoh: Pembangunan Bendungan..." required>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex-1 bg-indigo-600 text-white py-3 rounded-xl font-medium text-sm hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                                Simpan Paket
                            </button>
                            <button type="button" @click="openModal = false"
                                class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-medium text-sm hover:bg-slate-200 transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <style>
            body {
                background-color: #f8fafc;
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
            }

            [x-cloak] {
                display: none !important;
            }

            /* Custom Scrollbar */
            .overflow-y-auto::-webkit-scrollbar {
                width: 4px;
            }

            .overflow-y-auto::-webkit-scrollbar-track {
                background: #f1f5f9;
            }

            .overflow-y-auto::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 20px;
            }

            .overflow-y-auto::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        </style>
    @endsection
