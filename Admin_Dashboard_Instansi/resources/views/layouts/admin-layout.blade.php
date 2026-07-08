<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - E-Tamu BKPSDM Kab. Tegal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#f4f7fe] text-gray-800">

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR (Sama Persis Seperti Lampiran Gambar) -->
        <div class="w-72 bg-gradient-to-b from-[#1a365d] via-[#1e40af] to-[#2563eb] text-white flex flex-col justify-between p-5 relative shadow-xl z-10 flex-shrink-0">
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-white/10 rounded-lg"><i class="ri-menu-line text-xl"></i></div>
                    <div>
                        <h2 class="font-bold text-sm tracking-wide uppercase">E-TAMU DIGITAL</h2>
                        <p class="text-[11px] text-gray-300">BKPSDM Kab. Tegal</p>
                    </div>
                </div>

                <div class="bg-white/10 rounded-xl p-4 flex items-center gap-3 mb-6 border border-white/10 relative">
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center font-bold text-lg shadow-inner">A</div>
                    <div>
                        <h4 class="font-semibold text-sm leading-tight">Administrator</h4>
                        <p class="text-[11px] text-gray-300">Admin BKPSDM</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">198701012024001</p>
                    </div>
                    <span class="absolute -right-2 top-1/2 -translate-y-1/2 w-5 h-5 bg-blue-600 rounded-full border-2 border-white flex items-center justify-center text-[10px] cursor-pointer shadow-md"><i class="ri-arrow-left-s-line text-white"></i></span>
                </div>

                <!-- MENU NAVIGASI DENGAN HIGHLIGHT OTOMATIS -->
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition {{ Route::is('dashboard') ? 'bg-white/15 text-white font-medium shadow-sm' : 'text-gray-200 hover:bg-white/10' }}">
                        <i class="ri-dashboard-line text-lg"></i><span>Dashboard</span>
                    </a>
                    <a href="{{ route('data.tamu') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition {{ Route::is('data.tamu') ? 'bg-white/15 text-white font-medium shadow-sm' : 'text-gray-200 hover:bg-white/10' }}">
                        <i class="ri-user-shared-line text-lg"></i><span>Data Tamu</span>
                    </a>
                    <a href="{{ route('kunjungan.hari_ini') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition {{ Route::is('kunjungan.hari_ini') ? 'bg-white/15 text-white font-medium shadow-sm' : 'text-gray-200 hover:bg-white/10' }}">
                        <i class="ri-user-follow-line text-lg"></i><span>Kunjungan Hari Ini</span>
                    </a>
                    <a href="{{ route('jadwal.tamu') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition {{ Route::is('jadwal.tamu') ? 'bg-white/15 text-white font-medium shadow-sm' : 'text-gray-200 hover:bg-white/10' }}">
                        <i class="ri-calendar-todo-line text-lg"></i><span>Jadwal Tamu</span>
                    </a>
                    <a href="{{ route('laporan') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition {{ Route::is('laporan') ? 'bg-white/15 text-white font-medium shadow-sm' : 'text-gray-200 hover:bg-white/10' }}">
                        <i class="ri-file-chart-line text-lg"></i><span>Laporan</span>
                    </a>
                    <a href="{{ route('statistik') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition {{ Route::is('statistik') ? 'bg-white/15 text-white font-medium shadow-sm' : 'text-gray-200 hover:bg-white/10' }}">
                        <i class="ri-bar-chart-box-line text-lg"></i><span>Statistik</span>
                    </a>
                    <a href="{{ route('pengaturan') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition {{ Route::is('pengaturan') ? 'bg-white/15 text-white font-medium shadow-sm' : 'text-gray-200 hover:bg-white/10' }}">
                        <i class="ri-settings-4-line text-lg"></i><span>Pengaturan</span>
                    </a>
                </nav>
            </div>
            
            <!-- TOMBOL LOGOUT FORMAL -->
            <div>
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-200 hover:bg-red-600/20 hover:text-white transition text-sm font-medium">
                        <i class="ri-logout-box-r-line text-lg"></i><span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- MAIN CONTENT ROUTER CONTROLLER -->
        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </div>

</body>
</html>