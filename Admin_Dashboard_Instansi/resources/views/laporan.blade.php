@extends('layouts.admin-layout')

@section('title', 'Laporan Kunjungan Tamu')

@section('content')
<div class="space-y-6">
    
    {{-- HEADER HALAMAN --}}
    <div class="flex items-center gap-4 border-b border-gray-200/60 pb-5">
        <div class="p-2.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100">
            <i class="ri-file-chart-line text-2xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Laporan Kunjungan Tamu</h1>
            <p class="text-xs text-gray-400 mt-0.5">Kelola, cetak, dan arsipkan seluruh rangkuman rekam jejak data tamu BKPSDM.</p>
        </div>
    </div>

    {{-- KARTU STATISTIK KECIL --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-200/60 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Semua</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $totalTamu ?? 0 }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200/60 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Hari Ini</p>
            <h3 class="text-2xl font-black text-blue-600 mt-1">{{ $tamuHariIni ?? 0 }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200/60 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Bulan Ini</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $tamuBulanIni ?? 0 }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200/60 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tahun Ini</p>
            <h3 class="text-2xl font-black text-purple-600 mt-1">{{ $tamuTahunIni ?? 0 }}</h3>
        </div>
    </div>

    {{-- BAGIAN FILTER & TOMBOL UNDUH (EXCEL, PDF, PRINT) --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200/60">
        <form action="{{ route('laporan') }}" method="GET" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            
            {{-- Form Input Filter --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 flex-1">
                <div class="relative">
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari Nama Tamu..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-xs outline-none bg-gray-50/50 focus:border-blue-500 focus:bg-white transition-all text-gray-700">
                    <i class="ri-search-line absolute left-3 top-3.5 text-gray-400 text-xs"></i>
                </div>

                <div>
                    <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-600 outline-none bg-gray-50/50 focus:border-blue-500 focus:bg-white transition-all cursor-pointer">
                        <option value="">Status: Semua</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    </select>
                </div>

                <div>
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-500 outline-none bg-gray-50/50 focus:border-blue-500 focus:bg-white transition-all">
                </div>

                <div class="flex gap-2">
                    <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" class="w-full flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-500 outline-none bg-gray-50/50 focus:border-blue-500 focus:bg-white transition-all">
                    <button type="submit" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition" title="Terapkan Filter">
                        <i class="ri-filter-3-line"></i>
                    </button>
                </div>
            </div>

            {{-- Tombol Aksi Unduh Dokumen Terintegrasi --}}
            <div class="flex items-center gap-2 flex-wrap sm:justify-end">
                <a href="{{ route('laporan.excel', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition-all active:scale-98">
                    <i class="ri-file-excel-2-line text-sm"></i> 
                    <span>Export Excel</span>
                </a>
                
                <a href="{{ route('laporan.pdf', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-xs transition-all active:scale-98">
                    <i class="ri-file-pdf-line text-sm"></i> 
                    <span>Export PDF</span>
                </a>

                <a href="{{ route('laporan.print', request()->query()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-700 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-xs transition-all active:scale-98">
                    <i class="ri-printer-line text-sm"></i> 
                    <span>Print</span>
                </a>
            </div>
        </form>
    </div>

    {{-- TABEL DATA KUNJUNGAN TAMU --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200/70 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th class="p-4 w-16">No</th>
                        <th class="p-4">Nama</th>
                        <th class="p-4">Instansi</th>
                        <th class="p-4">Bidang/Tujuan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Jam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($tamus ?? [] as $tamu)
                        <tr class="hover:bg-blue-50/10 transition-colors">
                            <td class="p-4 font-medium text-gray-500">{{ $loop->iteration }}</td>
                            <td class="p-4 font-bold text-gray-950 capitalize">{{ $tamu->nama_lengkap ?? $tamu->nama }}</td>
                            <td class="p-4 text-gray-600">{{ $tamu->instansi }}</td>
                            <td class="p-4 text-gray-600">{{ $tamu->tujuan ?? $tamu->bidang }}</td>
                            <td class="p-4">
                                @if(($tamu->status ?? 'Selesai') == 'Selesai')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-600 text-xs font-semibold rounded-full border border-emerald-100">
                                        <i class="ri-checkbox-circle-fill text-[10px]"></i> Selesai
                                    </span>
                                @elseif($tamu->status == 'Diproses')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 text-amber-600 text-xs font-semibold rounded-full border border-amber-100">
                                        <i class="ri-loader-4-line text-[10px] animate-spin"></i> Diproses
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-rose-50 text-rose-600 text-xs font-semibold rounded-full border border-rose-100">
                                        <i class="ri-time-fill text-[10px]"></i> Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-xs font-medium text-gray-500">
                                {{ $tamu->created_at ? $tamu->created_at->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="p-4 text-xs font-mono text-gray-400">
                                {{ $tamu->created_at ? $tamu->created_at->format('H:i') : '-' }} WIB
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-20 text-gray-400 text-xs italic font-medium">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="ri-folder-open-line text-2xl text-gray-300"></i>
                                    <span>Tidak ada data kunjungan yang cocok dengan kriteria filter.</span>
                                </div>
                            </td>
                        </tr>
                    @endempty
                </tbody>
            </table>
        </div>
        
        {{-- BLOK LINK PAGINASI LARAVEL --}}
        @if(method_exists($tamus ?? null, 'links'))
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                {{ $tamus->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection