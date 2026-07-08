@extends('layouts.admin-layout')

@section('title', 'Kunjungan Hari Ini')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200/60 pb-5">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Kunjungan Hari Ini</h1>
            <p class="text-xs text-gray-400 mt-1">
                Data Live Tanggal: <span class="font-semibold text-gray-600">{{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</span>
            </p>
        </div>
        <button onclick="window.location.reload()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 active:scale-98 text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer">
            <i class="ri-refresh-line"></i>
            <span>Refresh Antrian</span>
        </button>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-200/60 shadow-sm text-center">
            <p class="text-[32px] font-black text-blue-600 tracking-tight">{{ $totalAntrian ?? 0 }}</p>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">Total Antrian</p>
        </div>
        
        <div class="bg-white p-5 rounded-2xl border border-gray-200/60 shadow-sm text-center">
            <p class="text-[32px] font-black text-emerald-600 tracking-tight">{{ $antrianSelesai ?? 0 }}</p>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">Selesai</p>
        </div>
        
        <div class="bg-white p-5 rounded-2xl border border-gray-200/60 shadow-sm text-center">
            <p class="text-[32px] font-black text-amber-500 tracking-tight">{{ $antrianDiproses ?? 0 }}</p>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">Sedang Diproses</p>
        </div>
        
        <div class="bg-white p-5 rounded-2xl border border-gray-200/60 shadow-sm text-center">
            <p class="text-[32px] font-black text-rose-500 tracking-tight">{{ $antrianMenunggu ?? 0 }}</p>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">Menunggu</p>
        </div>
    </div>

    <div class="space-y-3.5">
        @forelse($tamus ?? [] as $tamu)
            <div class="bg-white p-4 rounded-2xl border border-gray-200/60 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 transition-all hover:translate-x-1 duration-200
                @if($tamu->status == 'Selesai') border-l-4 border-l-emerald-500 
                @elseif($tamu->status == 'Diproses') border-l-4 border-l-amber-500 
                @else border-l-4 border-l-rose-500 @endif">
                
                <div class="flex items-center gap-4">
                    <span class="px-3 py-1.5 bg-gray-50 text-gray-700 font-mono text-xs font-bold rounded-lg border border-gray-200">
                        A{{ str_pad($tamu->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm capitalize">{{ $tamu->nama }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $tamu->instansi }} • <span class="text-gray-500">{{ $tamu->keperluan }}</span></p>
                    </div>
                </div>

                <div class="flex items-center justify-between sm:justify-end gap-6">
                    <span class="text-xs font-mono text-gray-400">
                        <i class="ri-time-line text-[11px]"></i> {{ $tamu->created_at->format('H:i') }} WIB
                    </span>
                    
                    <div>
                        @if($tamu->status == 'Selesai')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-full border border-emerald-100">
                                <i class="ri-checkbox-circle-fill text-[11px]"></i>
                                <span>Selesai</span>
                            </span>
                        @elseif($tamu->status == 'Diproses')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 text-xs font-bold rounded-full border border-amber-100 animate-pulse">
                                <i class="ri-loader-4-line text-[11px] animate-spin"></i>
                                <span>Diproses</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-600 text-xs font-bold rounded-full border border-rose-100">
                                <i class="ri-time-fill text-[11px]"></i>
                                <span>Menunggu</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200/60 p-16 text-center shadow-sm">
                <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-3 border border-gray-100">
                    <i class="ri-user-received-2-line"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-800">Belum Ada Antrian Tamu</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-xs mx-auto">Hari baru telah dimulai. Daftar antrian kunjungan hari ini akan muncul di sini secara otomatis jika ada tamu baru yang mengisi buku tamu.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection