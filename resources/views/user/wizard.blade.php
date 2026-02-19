@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
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
        <h2 class="text-xl font-bold mb-2">Tahap {{ $step }}</h2>
        <p class="text-gray-500 mb-6">{{ $paket->nama_paket }}</p>

        <form action="{{ route('user.wizard.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="paket_id" value="{{ $paket->id }}">
            <input type="hidden" name="step_number" value="{{ $step }}">

            @if($submission && $submission->file_path)
                <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-2xl">
                    <p class="text-sm text-blue-700 font-medium">File saat ini: 
                        <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="underline font-bold">Lihat Dokumen</a>
                    </p>
                </div>
            @endif

            <div class="border-2 border-dashed border-gray-200 rounded-2xl p-10 text-center">
                <input type="file" name="file_upload" class="mx-auto block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
            </div>

            <div class="flex justify-between mt-8">
                @if($step > 1)
                    <a href="{{ route('user.step', [$paket->id, $step - 1]) }}" class="px-6 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold">Sebelumnya</a>
                @else
                    <div></div>
                @endif

                <button type="submit" name="action" value="next" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold">
                    {{ $step == 11 ? 'Selesai & Kirim' : 'Simpan & Lanjut' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection