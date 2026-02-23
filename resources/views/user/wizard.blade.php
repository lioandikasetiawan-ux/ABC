@extends('layouts.user')

@section('content')
    <div class="w-full px-6 py-4">
        {{-- Progress Steps --}}
        <div class="mb-8 relative">
            <div class="absolute top-4 left-0 w-full h-0.5 bg-slate-200 -z-10"></div>
            <div class="flex justify-between overflow-x-auto pb-2 gap-1">
                @for ($i = 1; $i <= 11; $i++)
                    @php
                        $isCurrent = $step == $i;
                        $userSubmissions = $paket->submissions->where('user_id', auth()->id());
                        $currentSubmission = $userSubmissions->where('step_number', $i)->first();
                        $isCompleted = $currentSubmission && !empty($currentSubmission->file_path);
                        $isVerified = $currentSubmission && in_array($currentSubmission->status, ['disetujui', 'verified']);
                        $isRejected = $currentSubmission && in_array($currentSubmission->status, ['ditolak', 'rejected']);
                        $maxStepUser = $userSubmissions->whereNotNull('file_path')->max('step_number') ?? 0;
                        $canNavigate = $i <= $maxStepUser + 1;

                        $circleClass = 'bg-slate-100 text-indigo-600 hover:bg-indigo-100';
                        if ($isCurrent) {
                            $circleClass = 'bg-indigo-600 text-white ring-4 ring-indigo-100';
                        } elseif ($isRejected) {
                            $circleClass = 'bg-rose-500 text-white hover:bg-rose-600';
                        } elseif ($isVerified) {
                            $circleClass = 'bg-emerald-500 text-white hover:bg-emerald-600';
                        } elseif ($isCompleted) {
                            $circleClass = 'bg-slate-300 text-slate-700 hover:bg-slate-400';
                        }
                    @endphp

                    <div class="flex flex-col items-center min-w-[45px] relative z-10">
                        @if ($canNavigate)
                            <a href="{{ route('user.step', [$paket->id, $i]) }}"
                                class="w-9 h-9 rounded-xl flex items-center justify-center font-semibold text-sm transition-all {{ $circleClass }}">
                                @if ($isVerified && !$isCurrent)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @elseif ($isRejected && !$isCurrent)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @else
                                    <span class="text-xs">{{ $i }}</span>
                                @endif
                            </a>
                        @else
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 cursor-not-allowed">
                                <span class="text-xs">{{ $i }}</span>
                            </div>
                        @endif
                        <span class="text-[9px] font-medium mt-1.5 {{ $isCurrent ? 'text-indigo-600' : ($isRejected ? 'text-rose-600' : ($isVerified ? 'text-emerald-600' : 'text-slate-400')) }}">
                            S{{ $i }}
                        </span>
                    </div>

                    @if ($i < 11)
                        @php $lineColor = $isVerified ? 'bg-emerald-500' : ($isRejected ? 'bg-rose-500' : 'bg-slate-200'); @endphp
                        <div class="flex-1 h-0.5 mt-4 {{ $lineColor }}"></div>
                    @endif
                @endfor
            </div>
        </div>

        {{-- Main Card --}}
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-1 h-5 bg-indigo-600 rounded-full"></div>
                            <h2 class="text-lg font-semibold text-slate-800">Tahap {{ $step }}</h2>
                        </div>
                        <p class="text-sm text-slate-500 ml-3">{{ $paket->nama_paket }}</p>
                    </div>

                    @php
                        $totalCompleted = $paket->submissions->where('user_id', auth()->id())->whereNotNull('file_path')->unique('step_number')->count();
                        $progressPercent = round(($totalCompleted / 11) * 100);
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $progressPercent }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-indigo-600">{{ $progressPercent }}%</span>
                    </div>
                </div>

                @if($submission && in_array($submission->status, ['ditolak', 'rejected']))
                <div class="mx-6 mt-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-lg bg-rose-100 flex items-center justify-center flex-shrink-0 text-rose-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-rose-800">Catatan</p>
                            <p class="text-xs text-rose-700 mt-1 leading-relaxed italic">"{{ $submission->catatan_admin ?? 'Berkas ditolak, silakan unggah ulang.' }}"</p>
                        </div>
                    </div>
                </div>
                @endif

                @if ($errors->any())
                    <div class="mx-6 mt-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-lg bg-rose-100 flex items-center justify-center flex-shrink-0 text-rose-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            </div>
                            <p class="text-sm text-rose-700">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('user.wizard.store') }}" method="POST" enctype="multipart/form-data" id="upload-form" class="p-6">
                    @csrf
                    <input type="hidden" name="paket_id" value="{{ $paket->id }}">
                    <input type="hidden" name="step_number" value="{{ $step }}">

                    @if ($submission && $submission->file_path)
                        <div class="mb-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1.01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                <p class="text-xs font-medium text-slate-600">Dokumen Terupload</p>
                            </div>
                            <div class="grid grid-cols-1 gap-2">
                                @php $files = is_array($submission->file_path) ? $submission->file_path : [$submission->file_path]; @endphp
                                @foreach ($files as $file)
                                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-slate-100">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <span class="text-sm font-medium text-slate-700 truncate">{{ basename($file) }}</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                            </a>
                                            {{-- Kondisi: Hanya tampil di Step 8 dan jika belum diverifikasi --}}
                                            @if($step == 8 && !in_array($submission->status, ['disetujui', 'verified']))
                                            <button type="button" onclick="deleteExistingFile('{{ $file }}', '{{ $submission->id }}')" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="relative border-2 border-dashed rounded-xl p-8 text-center transition-colors {{ $errors->has('file_upload') ? 'border-rose-300 bg-rose-50/30' : 'border-slate-200 hover:border-indigo-400' }}">
                        <div class="flex flex-col items-center">
                            <div class="w-14 h-14 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 mb-3 border border-slate-200">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            </div>
                            <p id="file-status-text" class="text-sm font-medium text-slate-700 mb-1">{{ $step == 8 ? 'Klik untuk tambah berkas' : 'Pilih berkas' }}</p>
                            <div id="file-name-container" class="hidden w-full max-w-sm">
                                <div id="file-list" class="text-xs text-slate-600 space-y-1 mb-2 bg-slate-50 p-3 rounded-lg"></div>
                                <button type="button" onclick="resetFileSelection(event)" class="text-xs font-medium text-rose-600 hover:text-rose-700">Hapus Pilihan</button>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">PDF, JPG, PNG (Maks. 2MB)</p>
                            <div class="mt-4">
                                <div class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-medium shadow-sm pointer-events-none">Pilih File</div>
                            </div>
                        </div>
                        <input type="file" id="file-input-field" name="file_upload{{ $step == 8 ? '[]' : '' }}" {{ $step == 8 ? 'multiple' : '' }} onchange="updateFileDisplay()" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                    </div>

                    <div class="flex justify-between mt-6 pt-6 border-t border-slate-100">
                        @if ($step > 1)
                            <a href="{{ route('user.step', [$paket->id, $step - 1]) }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" /></svg>Kembali
                            </a>
                        @else
                            <div></div>
                        @endif
                        <button type="submit" name="action" value="next" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 shadow-lg shadow-indigo-200">
                            <span>{{ $step == 11 ? 'Selesai & Kirim' : 'Simpan & Lanjut' }}</span>
                            @if ($step < 11)
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let accumulatedFiles = new DataTransfer();
        function updateFileDisplay() {
            const input = document.getElementById('file-input-field');
            const container = document.getElementById('file-name-container');
            const fileList = document.getElementById('file-list');
            const statusText = document.getElementById('file-status-text');
            const isStep8 = "{{ $step }}" == "8";
            if (input.files.length > 0) {
                if (isStep8) {
                    Array.from(input.files).forEach(file => accumulatedFiles.items.add(file));
                    input.files = accumulatedFiles.files;
                }
                fileList.innerHTML = '';
                Array.from(input.files).forEach((f) => {
                    const item = document.createElement('div');
                    item.className = 'text-slate-700 text-xs flex items-center gap-2';
                    item.innerHTML = `<svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"/></svg><span class="truncate">${f.name}</span>`;
                    fileList.appendChild(item);
                });
                statusText.innerText = isStep8 ? `Terpilih (${input.files.length} file):` : "File terpilih:";
                container.classList.remove('hidden');
            }
        }
        function resetFileSelection(event) {
            if (event) event.stopPropagation();
            document.getElementById('file-input-field').value = "";
            accumulatedFiles = new DataTransfer();
            document.getElementById('file-list').innerHTML = '';
            document.getElementById('file-status-text').innerText = "{{ $step == 8 ? 'Klik untuk tambah berkas' : 'Pilih berkas' }}";
            document.getElementById('file-name-container').classList.add('hidden');
        }
        function deleteExistingFile(filePath, submissionId) {
            if (confirm('Hapus file ini secara permanen?')) {
                fetch("{{ route('user.wizard.delete-file') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ file_path: filePath, id: submissionId })
                }).then(response => response.json()).then(data => {
                    if (data.success) window.location.reload();
                    else alert('Gagal menghapus file.');
                });
            }
        }
    </script>
@endsection