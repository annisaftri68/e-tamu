@extends('layouts.admin-layout')

@section('title', 'Print Laporan Kunjungan Tamu')

@section('content')
<div class="bg-white p-6 rounded-3xl shadow-sm">
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold">Laporan Kunjungan Tamu</h1>
        <p class="text-sm text-gray-500 mt-1">Dicetak dari sistem laporan e-Tamu</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm text-gray-800">
            <thead>
                <tr class="bg-slate-100 text-xs uppercase tracking-wide text-slate-600">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Instansi</th>
                    <th class="px-4 py-3">Bidang / Tujuan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Jam</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tamus as $index => $tamu)
                    <tr class="border-b border-slate-200">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $tamu->nama }}</td>
                        <td class="px-4 py-3">{{ $tamu->instansi }}</td>
                        <td class="px-4 py-3">{{ $tamu->tujuan ?? $tamu->bidang }}</td>
                        <td class="px-4 py-3">{{ $tamu->status ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $tamu->created_at ? $tamu->created_at->translatedFormat('d M Y') : '-' }}</td>
                        <td class="px-4 py-3">{{ $tamu->created_at ? $tamu->created_at->format('H:i') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
