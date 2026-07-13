@extends('layouts.admin-layout')

@section('title', 'Data Tamu')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Data Tamu</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola seluruh data kunjungan tamu BKPSDM Kabupaten Tegal</p>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 w-full md:w-96">
            <i class="ri-search-line text-gray-400"></i>
            <input type="text" placeholder="Cari nama atau instansi..." class="bg-transparent text-sm w-full focus:outline-none">
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="p-4 text-sm font-semibold">No</th>
                    <th class="p-4 text-sm font-semibold">Nama</th>
                    <th class="p-4 text-sm font-semibold">NIK</th>
                    <th class="p-4 text-sm font-semibold">Instansi</th>
                    <th class="p-4 text-sm font-semibold">Keperluan</th>
                    <th class="p-4 text-sm font-semibold">Tujuan</th>
                    <th class="p-4 text-sm font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tamus ?? [] as $tamu)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-4 text-sm text-gray-600">{{ $loop->iteration }}</td>
                        <td class="p-4 text-sm font-medium text-gray-900">{{ $tamu->nama_lengkap ?? $tamu->nama ?? '-' }}</td>
                        <td class="p-4 text-sm text-gray-500">{{ $tamu->nik ?? '-' }}</td>
                        <td class="p-4 text-sm text-gray-600">{{ $tamu->instansi }}</td>
                        <td class="p-4 text-sm text-gray-600">{{ $tamu->keperluan }}</td>
                        <td class="p-4 text-sm text-gray-600">{{ $tamu->tujuan ?? $tamu->bidang ?? '-' }}</td>
                        <td class="p-4 text-sm text-center">
                            <div class="flex justify-center gap-3 text-lg">
                                <button class="text-blue-500 hover:text-blue-700 transition"><i class="ri-eye-line"></i></button>
                                <button onclick="bukaModalHapus('{{ $tamu->id }}', '{{ $tamu->nama }}')" class="text-red-500 hover:text-red-700 transition"><i class="ri-delete-bin-line"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-500">
                            Belum ada data tamu. Silakan tambahkan tamu atau periksa kembali tabel database.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="modalHapusTamu" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden p-6 text-center">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus Data</h3>
            <p class="text-sm text-gray-500 mb-4">Apakah Anda yakin ingin menghapus data dari <span id="namaTamuTerpilih" class="font-bold"></span>?</p>
            <div class="flex gap-3 justify-center">
                <button onclick="tutupModalHapus()" class="px-4 py-2 bg-gray-200 rounded-xl text-sm">Batal</button>
                <button id="btnKonfirmasiHapus" class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm">Hapus</button>
            </div>
        </div>
    </div>

    <script>
        function bukaModalHapus(id, nama) {
            document.getElementById('namaTamuTerpilih').innerText = nama;
            document.getElementById('modalHapusTamu').classList.remove('hidden');
        }
        function tutupModalHapus() {
            document.getElementById('modalHapusTamu').classList.add('hidden');
        }
    </script>
@endsection