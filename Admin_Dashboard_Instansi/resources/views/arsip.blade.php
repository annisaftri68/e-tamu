@extends('layouts.admin-layout')

@section('title', 'Arsip Tamu')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4 border-b border-gray-200/60 pb-5">
        <div class="p-2.5 bg-slate-50 text-slate-600 rounded-2xl border border-slate-100">
            <i class="ri-archive-2-line text-2xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Arsip Tamu</h1>
            <p class="text-xs text-gray-400 mt-0.5">Data tamu bulan sebelumnya otomatis dipindahkan ke arsip untuk kebutuhan laporan.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-200/70 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th class="p-4 w-16">No</th>
                        <th class="p-4">Nama</th>
                        <th class="p-4">Instansi</th>
                        <th class="p-4">Bidang/Tujuan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($tamus ?? [] as $tamu)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="p-4 font-medium text-gray-500">{{ $loop->iteration }}</td>
                            <td class="p-4 font-bold text-gray-950 capitalize">{{ $tamu->nama_lengkap ?? '-' }}</td>
                            <td class="p-4 text-gray-600">{{ $tamu->instansi ?? '-' }}</td>
                            <td class="p-4 text-gray-600">{{ $tamu->bidang ?? $tamu->tujuan ?? '-' }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-slate-50 text-slate-600 text-xs font-semibold rounded-full border border-slate-100">
                                    <i class="ri-folder-archive-line text-[10px]"></i> {{ $tamu->status ?? 'Arsip' }}
                                </span>
                            </td>
                            <td class="p-4 text-xs font-medium text-gray-500">
                                {{ $tamu->created_at ? $tamu->created_at->translatedFormat('d M Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-20 text-gray-400 text-xs italic font-medium">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="ri-inbox-archive-line text-2xl text-gray-300"></i>
                                    <span>Belum ada arsip tamu bulan sebelumnya.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($tamus ?? null, 'links'))
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                {{ $tamus->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
