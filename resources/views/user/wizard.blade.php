@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6">
    {{-- Progress Steps --}}
    <div class="flex items-center justify-between mb-10 overflow-x-auto pb-4">
        @for ($i = 1; $i <= 11; $i++)
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold mb-2
                    {{ $step == $i ? 'bg-blue-600 text-white' : ($step > $i ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400') }}">
                    {{ $step > $i ? '✓' : $i }}
                </div>
                <span class="text-[10px] uppercase font-bold {{ $step == $i ? 'text-blue-600' : 'text-gray-400' }}">Step {{ $i }}</span>
            </div>
            @if($i < 11) <div class="w-full h-0.5 bg-gray-100 mt-5"></div> @endif
        @endfor
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold mb-2 text-gray-800">Tahap {{ $step }}</h2>
        <p class="text-gray-500 mb-6">{{ $paket->nama_paket }}</p>

        {{-- BAGIAN NOTIFIKASI ERROR --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 animate-bounce">
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-black text-red-600 uppercase tracking-widest">Peringatan!</p>
                    <p class="text-sm text-red-500 font-medium">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('user.wizard.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="paket_id" value="{{ $paket->id }}">
            <input type="hidden" name="step_number" value="{{ $step }}">

            @if($submission && $submission->file_path)
                <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-2xl">
                    <p class="text-xs font-bold text-blue-800 uppercase mb-2">Dokumen Terupload:</p>
                    <div class="space-y-1">
                        @if(is_array($submission->file_path))
                            @foreach($submission->file_path as $file)
                                <a href="{{ asset('storage/'.$file) }}" target="_blank" class="block text-sm text-blue-600 underline font-medium hover:text-blue-800">
                                    {{ basename($file) }}
                                </a>
                            @endforeach
                        @else
                            <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="block text-sm text-blue-600 underline font-medium hover:text-blue-800">
                                {{ basename($submission->file_path) }}
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <div class="border-2 border-dashed {{ $errors->has('file_upload') ? 'border-red-300 bg-red-50/30' : 'border-gray-200' }} rounded-2xl p-10 text-center hover:border-blue-300 transition-colors">
                <p class="text-gray-600 mb-4 font-medium">
                    {{ $step == 8 ? 'Anda dapat mengunggah lebih dari satu berkas.' : 'Pilih berkas untuk diunggah.' }}
                </p>
                <input type="file" 
                       name="file_upload{{ $step == 8 ? '[]' : '' }}" 
                       {{ $step == 8 ? 'multiple' : '' }}
                       class="mx-auto block text-sm text-gray-500 file:mr-4 file:py-2 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer" />
                <p class="text-[10px] text-gray-400 mt-4">Format: PDF, JPG, PNG (Maks. 2MB)</p>
            </div>

            <div class="flex justify-between mt-8">
                @if($step > 1)
                    <a href="{{ route('user.step', [$paket->id, $step - 1]) }}" class="px-8 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition">Sebelumnya</a>
                @else
                    <div></div>
                @endif

                <button type="submit" name="action" value="next" class="px-10 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition">
                    {{ $step == 11 ? 'Selesai & Kirim' : 'Simpan & Lanjut' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection