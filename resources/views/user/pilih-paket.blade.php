@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Pilih Paket Hibah</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($pakets as $paket)
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition">
            <h3 class="text-lg font-bold text-blue-600 mb-4">{{ $paket->nama_paket }}</h3>
            <a href="{{ route('user.step', [$paket->id, 1]) }}" 
               class="block w-full text-center py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition">
                Mulai Upload (Step 1)
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection