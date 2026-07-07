<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - E-Tamu</title>
    <!-- Menggunakan Tailwind CSS CDN untuk styling cepat -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="w-64 bg-slate-800 text-white flex flex-col">
            <div class="p-5 text-xl font-bold border-b border-slate-700">
                E-Tamu Admin
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="#" class="block py-2.5 px-4 rounded bg-slate-900 text-white font-medium">Dashboard</a>
                <a href="#" class="block py-2.5 px-4 rounded text-slate-300 hover:bg-slate-700 hover:text-white transition">Data Tamu</a>
                <a href="#" class="block py-2.5 px-4 rounded text-slate-300 hover:bg-slate-700 hover:text-white transition">Instansi</a>
                <a href="#" class="block py-2.5 px-4 rounded text-slate-300 hover:bg-slate-700 hover:text-white transition">Laporan</a>
            </nav>
            <div class="p-4 border-t border-slate-700 text-sm text-slate-400">
                Logged in as Admin
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Topbar Header -->
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">Dashboard Overview</h1>
                <button class="text-gray-600 hover:text-gray-900 font-medium">Logout</button>
            </header>

            <!-- Content Body -->
            <main class="p-6 space-y-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                        <div class="text-sm font-medium text-gray-500 uppercase">Total Tamu Hari Ini</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">24 Orang</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                        <div class="text-sm font-medium text-gray-500 uppercase">Tamu Selesai</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">18 Orang</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
                        <div class="text-sm font-medium text-gray-500 uppercase">Menunggu Persetujuan</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">6 Orang</div>
                    </div>
                </div>

                <!-- Contoh Tabel Data Tamu -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Daftar Kunjungan Terbaru</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="p-3 text-sm font-semibold text-gray-600">Nama Tamu</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Instansi Tujuan</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Waktu Masuk</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="p-3 text-sm text-gray-700">Ahmad Fauzi</td>
                                    <td class="p-3 text-sm text-gray-700">Dinas Kominfo</td>
                                    <td class="p-3 text-sm text-gray-700">10:15 WIB</td>
                                    <td class="p-3 text-sm"><span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Selesai</span></td>
                                </tr>
                                <tr>
                                    <td class="p-3 text-sm text-gray-700">Riana Lestari</td>
                                    <td class="p-3 text-sm text-gray-700">Bagian Umum</td>
                                    <td class="p-3 text-sm text-gray-700">11:00 WIB</td>
                                    <td class="p-3 text-sm"><span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Berkunjung</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>
</html>