@extends('layouts.admin-layout')

@section('title', 'Agenda Jadwal Kunjungan')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg text-blue-700">
                    <i class="ri-calendar-event-line text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Agenda Jadwal Kunjungan</h1>
            </div>
            <p class="text-gray-500 text-sm mt-1 ml-12">
                Persetujuan, manajemen waktu, dan verifikasi status kehadiran tamu
            </p>
        </div>

        <button onclick="toggleModal(true)" class="flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow-md shadow-blue-600/20">
            <i class="ri-add-line text-lg"></i> Tambah Jadwal
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-4 mb-6 border border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="relative flex items-center">
            <i class="ri-search-line absolute left-3 text-gray-400 text-lg"></i>
            <input type="text" placeholder="Cari nama tamu..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        </div>
        <div class="relative flex items-center">
            <i class="ri-calendar-line absolute left-3 text-gray-400 text-lg"></i>
            <input type="date" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 outline-none focus:ring-2 focus:ring-blue-500 text-sm text-gray-600">
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#1e293b] text-white text-sm">
                        <th class="px-6 py-4 text-left font-semibold">Tamu & Instansi</th>
                        <th class="px-6 py-4 text-left font-semibold">Tujuan & Keperluan</th>
                        <th class="px-6 py-4 text-left font-semibold">Waktu Pelaksanaan</th>
                        <th class="px-6 py-4 text-center font-semibold">Status</th>
                        <th class="px-6 py-4 text-center font-semibold">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="text-center py-20 text-gray-400">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-2xl">
                                    <i class="ri-error-warning-line"></i>
                                </div>
                                <span class="text-sm font-medium">Tidak ditemukan jadwal kunjungan yang cocok.</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalJadwal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-gray-100">
            <div class="bg-[#1e293b] px-6 py-4 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Form Tambah Jadwal Baru</h3>
                <button onclick="toggleModal(false)" class="text-gray-400 hover:text-white transition text-xl">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            
            <form class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Tamu</label>
                    <input type="text" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Masukkan nama lengkap">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Instansi / Organisasi</label>
                    <input type="text" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Nama instansi/sekolah/pribadi">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Bidang Tujuan</label>
                    <select class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                        <option value="Sekretariat">Sekretariat</option>
                        <option value="Mutasi">Bidang Mutasi</option>
                        <option value="Banglir">Bidang Banglir</option>
                        <option value="PPIK">Bidang PPIK</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Kunjungan</label>
                        <input type="date" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Jam Berkunjung</label>
                        <input type="time" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Maksud / Keperluan</label>
                    <textarea rows="3" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none" placeholder="Tulis keperluan secara singkat dan jelas"></textarea>
                </div>
                
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="toggleModal(false)" class="px-4 py-2.5 border border-gray-200 hover:bg-gray-50 rounded-xl text-sm font-medium text-gray-600 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(show) {
            const modal = document.getElementById('modalJadwal');
            if(show) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }
    </script>
@endsection