@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    
    <div class="flex items-center justify-between mb-12 relative">
        <div class="absolute top-5 left-0 w-full h-0.5 bg-gray-200 -z-10"></div>
        @for ($i = 1; $i <= 11; $i++)
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300
                    {{ $step == $i ? 'bg-blue-600 text-white ring-4 ring-blue-100' : ($step > $i ? 'bg-green-500 text-white' : 'bg-white border-2 border-gray-200 text-gray-400') }}">
                    @if($step > $i) ✓ @else {{ $i }} @endif
                </div>
                <span class="text-[10px] mt-2 font-bold uppercase tracking-tighter {{ $step == $i ? 'text-blue-600' : 'text-gray-400' }}">Step {{ $i }}</span>
            </div>
        @endfor
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Langkah ke-{{ $step }}</h2>
                <p class="text-sm text-gray-500">{{ $paket->nama_paket }}</p>
            </div>
            <span class="px-4 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Progress: {{ round(($step/11)*100) }}%</span>
        </div>

        <form action="{{ route('user.wizard.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <input type="hidden" name="paket_id" value="{{ $paket->id }}">
            <input type="hidden" name="step_number" value="{{ $step }}">

            <div class="space-y-6">
                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-12 text-center hover:border-blue-400 transition-colors group">
                    @if($submission && $submission->file_path)
                        <div class="mb-6 inline-flex items-center p-3 bg-green-50 rounded-xl text-green-700 border border-green-100">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                            <span class="text-sm font-medium">File sudah ada: 
                                <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="underline font-bold">Lihat Dokumen</a>
                            </span>
                        </div>
                    @endif

                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-300 group-hover:text-blue-500 mb-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="text-gray-600 font-medium">Pilih file untuk diunggah</p>
                        <p class="text-xs text-gray-400 mt-1">Format: PDF, JPG, PNG (Maks. 2MB)</p>
                        <input type="file" name="file_upload" class="mt-6 block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                    </div>
                </div>

                @if($submission)
                <div class="flex items-center gap-4 p-4 rounded-2xl {{ $submission->status == 'rejected' ? 'bg-red-50' : 'bg-blue-50' }}">
                    <div class="text-sm">
                        <span class="font-bold block uppercase text-[10px] tracking-widest text-gray-400">Status Terakhir:</span>
                        <span class="font-bold {{ $submission->status == 'rejected' ? 'text-red-600' : 'text-blue-600' }}">
                            {{ strtoupper($submission->status) }}
                        </span>
                        @if($submission->catatan_admin)
                            <p class="mt-1 text-red-700 italic font-medium">"{{ $submission->catatan_admin }}"</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="flex justify-between mt-12 pt-8 border-t border-gray-100">
                @if($step > 1)
                    <a href="{{ route('user.step', [$paket->id, $step - 1]) }}" class="flex items-center px-8 py-3 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
                        Sebelumnya
                    </a>
                @else
                    <div></div>
                @endif

                <div class="flex gap-3">
                    <button type="submit" name="action" value="save" class="px-8 py-3 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition">Simpan Draft</button>
                    <button type="submit" name="action" value="next" class="flex items-center px-10 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition">
                        {{ $step == 11 ? 'Selesai & Kirim' : 'Simpan & Lanjut' }}
                        @if($step < 11)
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                        @endif
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection