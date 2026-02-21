@extends('layouts.app')

@section('content')
    <div class="w-full px-6 py-4">
        {{-- Alert Success / Error --}}
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

        {{-- Header Section --}}
        <div
            class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8 bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
            <div>
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center text-xs font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1 transition-transform group-hover:-translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Dashboard
                </a>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-1 h-8 bg-indigo-600 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-slate-800">Verifikasi: {{ $user->name }}</h2>
                </div>
                <div class="flex items-center gap-2 ml-4">
                    <span class="text-xs font-medium text-slate-400">Paket:</span>
                    <span class="text-sm font-semibold text-indigo-600">{{ $paket->nama_paket }}</span>
                </div>
            </div>

            {{-- Progress Card --}}
            <div class="bg-indigo-50 px-8 py-5 rounded-xl border border-indigo-100 text-center min-w-[200px]">
                <span class="block text-xs font-medium text-indigo-600 mb-2">Total Progress</span>
                <div class="flex items-center justify-center gap-2">
                    <span
                        class="text-3xl font-bold text-indigo-700">{{ $submissions->whereIn('status', ['disetujui', 'verified'])->count() }}</span>
                    <span class="text-lg text-indigo-400">/</span>
                    <span class="text-lg text-indigo-400">11</span>
                </div>
                <div class="w-full h-1.5 bg-indigo-200 rounded-full mt-3">
                    <div class="h-full bg-indigo-600 rounded-full"
                        style="width: {{ ($submissions->whereIn('status', ['disetujui', 'verified'])->count() / 11) * 100 }}%">
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Tahapan --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 w-16 text-center">No</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500">Tahapan & Dokumen</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 w-40 text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 w-1/3">Tindakan Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @php
                            $labels = [
                                1 => 'Pernyataan Kesediaan Menerima Hibah',
                                2 => 'Permohonan Anggota Tim Internal',
                                3 => 'Pembentukan Tim Internal',
                                4 => 'Berita Acara Penelitian',
                                5 => 'Saran Teknis',
                                6 => 'Rekomendasi Teknis',
                                7 => 'Persetujuan Hibah',
                                8 => 'Penyusunan BAST & NPHD',
                                9 => 'SK Penghapusan',
                                10 => 'Transaksi SAKTI',
                                11 => 'Laporan KPKNL',
                            ];
                        @endphp

                        @for ($i = 1; $i <= 11; $i++)
                            @php
                                $sub = $submissions->where('step_number', $i)->first();
                                $fileList = [];

                                if ($sub && $sub->file_path) {
                                    if (is_array($sub->file_path)) {
                                        $fileList = $sub->file_path;
                                    } else {
                                        $decoded = json_decode($sub->file_path, true);
                                        $fileList = is_array($decoded) ? $decoded : [$sub->file_path];
                                    }
                                }

                                $statusConfig = [
                                    'colors' => [
                                        'disetujui' => ['bg-emerald-100 text-emerald-700', 'CheckCircle'],
                                        'verified' => ['bg-emerald-100 text-emerald-700', 'CheckCircle'],
                                        'ditolak' => ['bg-rose-100 text-rose-700', 'XCircle'],
                                        'pending' => ['bg-amber-100 text-amber-700', 'Clock'],
                                        'default' => ['bg-slate-100 text-slate-500', 'File'],
                                    ],
                                ];

                                $statusKey = $sub->status ?? 'default';
                                if (in_array($statusKey, ['disetujui', 'verified'])) {
                                    $statusKey = 'disetujui';
                                }

                                $statusClass =
                                    $statusConfig['colors'][$statusKey] ?? $statusConfig['colors']['default'];
                            @endphp

                            <tr class="hover:bg-slate-50/80 transition-colors">
                                {{-- Nomor --}}
                                <td class="px-6 py-6 text-center">
                                    <span
                                        class="text-lg font-bold text-slate-300">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>

                                {{-- Tahapan & Dokumen --}}
                                <td class="px-6 py-6">
                                    <h3 class="text-sm font-semibold text-slate-800 mb-3">{{ $labels[$i] }}</h3>
                                    <div class="flex flex-col gap-2">
                                        @forelse($fileList as $index => $file)
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-600 text-xs rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition-all group max-w-full border border-slate-200">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2">
                                                    <path
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <span class="truncate max-w-[200px]">{{ basename($file) }}</span>
                                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    stroke-width="2">
                                                    <path
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                    </path>
                                                </svg>
                                            </a>
                                        @empty
                                            <span class="text-xs text-slate-400">Belum ada berkas diunggah</span>
                                        @endforelse
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-6 text-center align-top">
                                    @if ($sub)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $statusClass[0] }} text-xs font-medium rounded-lg">
                                            @switch($statusClass[1])
                                                @case('CheckCircle')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @break

                                                @case('XCircle')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                @break

                                                @case('Clock')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @break

                                                @default
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12h6m-6 4h6m2-10H7a2 2 0 00-2 2v14l4-4h10a2 2 0 002-2V6a2 2 0 00-2-2z">
                                                        </path>
                                                    </svg>
                                            @endswitch
                                            {{ $sub->status == 'disetujui' ? 'Disetujui' : ($sub->status == 'ditolak' ? 'Ditolak' : 'Menunggu') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-300">—</span>
                                    @endif
                                </td>

                                {{-- Tindakan Verifikasi --}}
                                <td class="px-6 py-6 align-top">
                                    @if ($sub)
                                        {{-- Form Utama --}}
                                        <form action="{{ route('admin.verify.submit', $sub->id) }}" method="POST"
                                            id="form-{{ $sub->id }}">
                                            @csrf
                                            <input type="hidden" name="status" id="status_hidden_{{ $sub->id }}">
                                            <input type="hidden" name="catatan_admin"
                                                id="catatan_hidden_{{ $sub->id }}">
                                        </form>

                                        {{-- Input Catatan (Visible) --}}
                                        <div class="space-y-3">
                                            <textarea id="catatan_{{ $sub->id }}" placeholder="Tidak ada catatan" rows="2" readonly
                                                class="w-full p-3 bg-slate-50/50 border border-slate-200 rounded-lg text-sm text-slate-500 cursor-default resize-none focus:outline-none">{{ $sub->catatan_admin }}</textarea>

                                            <div class="flex gap-2">
                                                <button type="button" onclick="handleApprove({{ $sub->id }})"
                                                    class="flex-1 px-4 py-2.5 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-lg hover:bg-emerald-600 hover:text-white transition-all border border-emerald-200 hover:border-emerald-600">
                                                    <span class="flex items-center justify-center gap-1.5">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="2">
                                                            <path d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Setujui
                                                    </span>
                                                </button>

                                                {{-- Tombol Tolak dengan Modal Sederhana --}}
                                                <button type="button" onclick="openRejectModal({{ $sub->id }})"
                                                    class="flex-1 px-4 py-2.5 bg-rose-100 text-rose-700 text-xs font-medium rounded-lg hover:bg-rose-600 hover:text-white transition-all border border-rose-200 hover:border-rose-600">
                                                    <span class="flex items-center justify-center gap-1.5">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="2">
                                                            <path d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Tolak
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">Tidak ada data untuk diverifikasi</span>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Reject Modal (Single instance) --}}
    <div id="rejectModal"
        class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-md">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-1 h-8 bg-rose-600 rounded-full"></div>
                <h3 class="text-lg font-semibold text-slate-800">Tolak Dokumen</h3>
            </div>

            <p class="text-sm text-slate-500 mb-4">Berikan alasan penolakan agar user dapat memperbaiki dokumen.</p>

            <textarea id="rejectReason"
                class="w-full p-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent mb-4"
                rows="4" placeholder="Contoh: File tidak terbaca, dokumen tidak lengkap, atau data tidak sesuai."></textarea>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeRejectModal()"
                    class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
                    Batal
                </button>
                <button type="button" onclick="submitReject()"
                    class="px-4 py-2 text-sm font-medium bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors shadow-sm">
                    Kirim Penolakan
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentSubId = null;

        function handleApprove(subId) {
            const catatan = document.getElementById('catatan_' + subId);
            const form = document.getElementById('form-' + subId);
            const hiddenStatus = document.getElementById('status_hidden_' + subId);
            const hiddenCatatan = document.getElementById('catatan_hidden_' + subId);

            if (confirm('Setujui dokumen ini?')) {
                hiddenStatus.value = 'disetujui';
                hiddenCatatan.value = catatan.value;
                form.submit();
            }
        }

        function openRejectModal(subId) {
            currentSubId = subId;
            document.getElementById('rejectReason').value = ''; // Kosongkan textarea
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
            currentSubId = null;
        }

        function submitReject() {
            if (!currentSubId) return;

            const catatan = document.getElementById('catatan_' + currentSubId);
            const rejectReason = document.getElementById('rejectReason');
            const form = document.getElementById('form-' + currentSubId);
            const hiddenStatus = document.getElementById('status_hidden_' + currentSubId);
            const hiddenCatatan = document.getElementById('catatan_hidden_' + currentSubId);

            const alasan = rejectReason.value.trim();

            if (!alasan) {
                alert('Alasan penolakan wajib diisi!');
                rejectReason.classList.add('ring-2', 'ring-rose-500', 'bg-rose-50');
                return;
            }

            hiddenStatus.value = 'ditolak';
            hiddenCatatan.value = alasan;
            form.submit();
        }

        // Remove ring when user starts typing
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.classList.remove('ring-2', 'ring-rose-500', 'bg-rose-50');
            });
        });

        // Close modal when clicking outside (optional)
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
@endsection
