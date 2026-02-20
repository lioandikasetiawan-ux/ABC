@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto py-4"> {{-- Diperkecil dari max-w-5xl dan py-6 --}}
    {{-- Progress Steps --}}
    <div class="flex items-center justify-between mb-6 overflow-x-auto pb-2"> {{-- Margin mb-10 ke mb-6 --}}
        @for ($i = 1; $i <= 11; $i++)
            @php
                $isCurrent = ($step == $i);
                $userSubmissions = $paket->submissions->where('user_id', auth()->id());
                $isCompleted = $userSubmissions->where('step_number', $i)->whereNotNull('file_path')->isNotEmpty();
                $maxStepUser = $userSubmissions->whereNotNull('file_path')->max('step_number') ?? 0;
                $canNavigate = ($i <= ($maxStepUser + 1));
            @endphp

            <div class="flex flex-col items-center flex-1 min-w-[50px]"> {{-- min-w diperkecil --}}
                @if($canNavigate)
                    <a href="{{ route('user.step', [$paket->id, $i]) }}" 
                       class="w-8 h-8 rounded-full flex items-center justify-center font-bold mb-1 transition-colors {{-- Ukuran w-10 ke w-8 --}}
                        {{ $isCurrent ? 'bg-blue-600 text-white ring-2 ring-blue-100' : ($isCompleted ? 'bg-green-500 text-white hover:bg-green-600' : 'bg-blue-100 text-blue-600 hover:bg-blue-200') }}">
                        @if($isCompleted && !$isCurrent)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        @else
                            <span class="text-xs">{{ $i }}</span>
                        @endif
                    </a>
                @else
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold mb-1 bg-gray-200 text-gray-400 cursor-not-allowed">
                        <span class="text-xs">{{ $i }}</span>
                    </div>
                @endif
                <span class="text-[9px] uppercase font-black tracking-tighter {{ $isCurrent ? 'text-blue-600' : ($isCompleted ? 'text-green-600' : 'text-gray-400') }}">
                    S{{ $i }}
                </span>
            </div>
            @if($i < 11) 
                <div class="w-full h-0.5 mt-4 -mx-1 rounded-full {{ $isCompleted ? 'bg-green-500' : 'bg-gray-100' }}"></div> 
            @endif
        @endfor
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100"> {{-- Padding p-8 ke p-6 --}}
        <div class="flex justify-between items-center mb-4"> {{-- items-start ke items-center, mb-6 ke mb-4 --}}
            <div>
                <h2 class="text-xl font-black text-gray-800 leading-tight">Tahap {{ $step }}</h2>
                <p class="text-xs text-gray-500 font-medium">{{ $paket->nama_paket }}</p>
            </div>
            <div class="px-3 py-1.5 bg-blue-50 rounded-xl border border-blue-100 text-right">
                <p class="text-[9px] font-bold text-blue-600 uppercase">Progres</p>
                @php
                    $totalCompleted = $paket->submissions->where('user_id', auth()->id())->whereNotNull('file_path')->unique('step_number')->count();
                @endphp
                <p class="text-sm font-black text-blue-700">{{ round(($totalCompleted / 11) * 100) }}%</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-100 rounded-xl flex items-center gap-3">
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-xs text-red-500 font-bold">{{ $errors->first() }}</p>
            </div>
        @endif

        <form action="{{ route('user.wizard.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
            @csrf
            <input type="hidden" name="paket_id" value="{{ $paket->id }}">
            <input type="hidden" name="step_number" value="{{ $step }}">

            {{-- Dokumen Terupload --}}
            @if($submission && $submission->file_path)
                <div class="mb-4 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                    <p class="text-[9px] font-black text-slate-400 uppercase mb-2 tracking-widest italic">Dokumen Terupload:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @php $files = is_array($submission->file_path) ? $submission->file_path : [$submission->file_path]; @endphp
                        @foreach($files as $file)
                            <div class="flex items-center justify-between p-2 bg-white border border-slate-200 rounded-lg">
                                <div class="flex items-center gap-2 overflow-hidden">
                                    <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <span class="text-[11px] font-bold text-gray-700 truncate">{{ basename($file) }}</span>
                                </div>
                                <div class="flex gap-1">
                                    <a href="{{ asset('storage/'.$file) }}" target="_blank" class="text-[9px] font-black text-blue-600 uppercase px-1.5 py-0.5 bg-blue-50 rounded">Buka</a>
                                    <button type="button" onclick="deleteExistingFile('{{ $file }}', '{{ $submission->id }}')" class="text-[9px] font-black text-red-600 uppercase px-1.5 py-0.5 bg-red-50 rounded">Hapus</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Area Upload --}}
            <div class="group relative border-2 border-dashed {{ $errors->has('file_upload') ? 'border-red-300 bg-red-50/30' : 'border-gray-200 hover:border-blue-400' }} rounded-2xl p-6 text-center transition-colors"> {{-- p-10 ke p-6 --}}
                <div class="mb-2">
                    <div class="w-10 h-10 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    
                    <p id="file-status-text" class="text-sm font-bold text-gray-600 mb-1">
                        {{ $step == 8 ? 'Klik untuk tambah berkas' : 'Pilih berkas' }}
                    </p>

                    <div id="file-name-container" class="hidden flex flex-col items-center">
                        <div id="file-list" class="text-[11px] text-gray-500 space-y-0.5 mb-2"></div>
                        <button type="button" onclick="resetFileSelection(event)" class="relative z-10 text-[9px] font-black text-red-500 uppercase hover:underline">
                            Hapus Pilihan
                        </button>
                    </div>

                    <p class="text-[10px] text-gray-400 mt-1">PDF, JPG, PNG (Maks. 2MB)</p>
                </div>

                <input type="file" 
                       id="file-input-field"
                       name="file_upload{{ $step == 8 ? '[]' : '' }}" 
                       {{ $step == 8 ? 'multiple' : '' }}
                       onchange="updateFileDisplay()"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                
                <div class="inline-flex items-center px-4 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-[10px] font-black uppercase pointer-events-none group-hover:bg-blue-600 group-hover:text-white transition-all">
                    Pilih File
                </div>
            </div>

            <div class="flex justify-between mt-6"> {{-- mt-10 ke mt-6 --}}
                @if($step > 1)
                    <a href="{{ route('user.step', [$paket->id, $step - 1]) }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-black hover:bg-slate-200 text-xs uppercase transition">Kembali</a>
                @else
                    <div></div>
                @endif

                <button type="submit" name="action" value="next" class="px-8 py-2.5 bg-blue-600 text-white rounded-xl font-black shadow-lg shadow-blue-100 hover:bg-blue-700 text-xs uppercase transition">
                    {{ $step == 11 ? 'Selesai & Kirim' : 'Simpan & Lanjut' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// (Script tetap sama seperti sebelumnya, tidak berpengaruh pada ukuran tampilan visual secara signifikan)
let accumulatedFiles = new DataTransfer();

function updateFileDisplay() {
    const input = document.getElementById('file-input-field');
    const container = document.getElementById('file-name-container');
    const fileList = document.getElementById('file-list');
    const statusText = document.getElementById('file-status-text');
    const isStep8 = "{{ $step }}" == "8";

    if (input.files.length > 0) {
        if (isStep8) {
            Array.from(input.files).forEach(file => {
                accumulatedFiles.items.add(file);
            });
            input.files = accumulatedFiles.files;
        }

        fileList.innerHTML = '';
        const nameCount = {};

        Array.from(input.files).forEach((f) => {
            const item = document.createElement('div');
            item.className = 'text-gray-700 font-medium flex items-center gap-1';
            let displayName = f.name;
            if (nameCount[f.name]) {
                const parts = f.name.split('.');
                const ext = parts.pop();
                const name = parts.join('.');
                displayName = `${name}-${nameCount[f.name]}.${ext}`;
                nameCount[f.name]++;
                item.classList.add('text-blue-600');
            } else {
                nameCount[f.name] = 1;
            }
            item.innerText = displayName;
            fileList.appendChild(item);
        });

        statusText.innerText = isStep8 ? `Terpilih (${input.files.length}):` : "Terpilih:";
        container.classList.remove('hidden');
    }
}

function resetFileSelection(event) {
    if(event) event.stopPropagation();
    const input = document.getElementById('file-input-field');
    const container = document.getElementById('file-name-container');
    const statusText = document.getElementById('file-status-text');
    const fileList = document.getElementById('file-list');
    input.value = "";
    accumulatedFiles = new DataTransfer();
    fileList.innerHTML = '';
    statusText.innerText = "{{ $step == 8 ? 'Klik untuk tambah berkas' : 'Pilih berkas' }}";
    container.classList.add('hidden');
}

function deleteExistingFile(filePath, submissionId) {
    if (confirm('Hapus file ini secara permanen?')) {
        fetch("{{ route('user.wizard.delete-file') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ file_path: filePath, id: submissionId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) { window.location.reload(); }
            else { alert('Gagal menghapus file.'); }
        });
    }
}
</script>
@endsection