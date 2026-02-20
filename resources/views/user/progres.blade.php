@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 pt-2 pb-8">
    <div class="mb-5">
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">
            Pantau <span class="text-blue-600">Progres Hibah</span>
        </h2>
        <p class="text-sm text-gray-500 mt-1">Status verifikasi dokumen secara real-time untuk setiap paket pekerjaan.</p>
    </div>

    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-gray-100">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Paket Pekerjaan</th>
                        <th class="px-4 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Tahapan Selesai</th>
                        <th class="px-4 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Progres Verifikasi</th>
                        <th class="px-4 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Visual Progres</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pakets as $p)
                    <tr class="hover:bg-blue-50/20 transition-colors group">
                        <td class="px-8 py-5">
                            <span class="text-[13px] font-bold text-gray-800 leading-tight block">{{ $p->nama_paket }}</span>
                        </td>
                        <td class="px-4 py-5 text-center">
                            <div class="inline-flex flex-col">
                                <span class="text-sm font-black text-gray-700">{{ $p->step_selesai }}<span class="text-gray-400 text-xs font-medium">/11</span></span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Unggahan</span>
                            </div>
                        </td>
                        <td class="px-4 py-5 text-center">
                            <div class="inline-flex flex-col">
                                <span class="text-sm font-black {{ $p->step_verifikasi == 11 ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ $p->step_verifikasi }}<span class="text-gray-400 text-xs font-medium">/11</span>
                                </span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Verified</span>
                            </div>
                        </td>
                        <td class="px-4 py-5 text-center whitespace-nowrap">
                            @if($p->step_verifikasi >= 11)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase bg-green-500 text-white shadow-sm">
                                    <svg class="w-2.5 h-2.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    Selesai
                                </span>
                            @elseif($p->step_selesai >= 11)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase bg-green-50 text-green-600 border border-green-100">
                                    <span class="w-1 h-1 rounded-full bg-green-500 mr-1.5 animate-pulse"></span>Terpenuhi
                                </span>
                            @elseif($p->step_selesai > 0)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase bg-blue-50 text-blue-600 border border-blue-100">
                                    <span class="w-1 h-1 rounded-full bg-blue-500 mr-1.5"></span>On Progres
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase bg-gray-50 text-gray-400 border border-gray-200">Belum Mulai</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 w-48">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 bg-gray-100 h-2 rounded-full overflow-hidden shadow-inner">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $p->step_verifikasi == 11 ? 'bg-green-500' : 'bg-gradient-to-r from-blue-500 to-blue-600' }}" 
                                         style="width: {{ ($p->step_verifikasi / 11) * 100 }}%"></div>
                                </div>
                                <span class="text-[11px] font-black text-gray-700 w-8">{{ round(($p->step_verifikasi / 11) * 100) }}%</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right whitespace-nowrap">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('user.progres.detail', $p->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2"/></svg>
                                </a>
                                @if($p->step_verifikasi < 11)
                                <a href="{{ route('user.step', [$p->id, 1]) }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-[11px] font-black text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all shadow-sm">
                                    KELOLA <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"/></svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-8 py-20 text-center text-gray-400 text-sm">Belum ada paket pekerjaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection