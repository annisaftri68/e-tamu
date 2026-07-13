<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// IMPORT CONTROLLER NYATA ANDA DI SINI
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- HANYA BISA DIAKSES JIKA BELUM LOGIN (GUEST) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        // 1. Validasi Input Form Login
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Deteksi otomatis apakah input berupa NIP (angka) atau Username biasa
        $fieldType = filter_var($credentials['username'], FILTER_VALIDATE_INT) ? 'nip' : 'username';

        // 3. Proses Autentikasi Nyata ke Database
        if (Auth::attempt([$fieldType => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        // 4. Jika Salah, Kembalikan Error
        return back()->withErrors([
            'loginError' => 'Username/NIP atau Kata Sandi yang Anda masukkan salah!',
        ])->onlyInput('username');
    })->name('login.proses');
});


// --- HANYA BISA DIAKSES JIKA SUDAH LOGIN (AUTH) ---
Route::middleware('auth')->group(function () {
    
    // Proses Keluar Sesi Keamanan
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

    // ==========================================================
    //  JALUR ROUTE UTAMA (SUDAH TERHUBUNG CONTROLLER)
    // ==========================================================

    // Panel Utama Admin Dashboard (Menggunakan DashboardController)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Kunjungan Hari Ini (Menggunakan KunjunganController untuk data LIVE & Auto Reset)
    Route::get('/kunjungan-hari-ini', [KunjunganController::class, 'index'])->name('kunjungan.hari_ini');

    // Laporan Kunjungan Tamu (Menggunakan LaporanController untuk Filter & Eksport)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    
    // Penunjang Aksi Dokumen di Menu Laporan
    Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');

    // ==========================================================
    //  JALUR ROUTE PEMELIHARAAN LAINNYA
    // ==========================================================

    Route::get('/data-tamu', function () {
        \App\Models\Tamu::archiveMonthly();

        $tamus = \App\Models\Tamu::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->latest()
            ->get();

        return view('data-tamu', compact('tamus'));
    })->name('data.tamu');

    Route::get('/arsip', function () {
        $tamus = \App\Models\TamuArsip::latest()->paginate(15);
        return view('arsip', compact('tamus'));
    })->name('arsip');

    Route::get('/jadwal-tamu', function () {
        return view('jadwal-tamu');
    })->name('jadwal.tamu');

    Route::get('/statistik', function () {
        $totalTamu = \App\Models\Tamu::count();
        $menunggu = \App\Models\Tamu::where('status', 'Menunggu')->count();
        $selesai = \App\Models\Tamu::where('status', 'Selesai')->count();

        $statistikLabels = [];
        $statistikData = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $statistikLabels[] = $bulan->translatedFormat('M');
            $statistikData[] = \App\Models\Tamu::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }

        $bidangLabels = ['Sekretariat', 'MUTPRO', 'PSDM', 'PPEKA'];
        $bidangData = [];
        foreach ($bidangLabels as $bidang) {
            $bidangData[] = \App\Models\Tamu::where('bidang', $bidang)->count();
        }

        return view('statistik', compact(
            'totalTamu',
            'menunggu',
            'selesai',
            'statistikLabels',
            'statistikData',
            'bidangLabels',
            'bidangData'
        ));
    })->name('statistik');


    // --- GRUP LOGIK MENU PENGATURAN & MANAGEMENT USER ---
    Route::get('/pengaturan', function () {
        // Mengambil semua list admin dari database untuk ditampilkan pada tabel manajemen pengguna
        $users = \App\Models\User::all();
        return view('pengaturan', compact('users'));
    })->name('pengaturan');

    // Proses Tambah Admin Baru - LOCKDOWN: Khusus Super Admin (adminbkpsdm)
    Route::post('/pengaturan/tambah-admin', function (Request $request) {
        // Proteksi Backend: Jika bukan super admin, gagalkan proses pembuatan akun
        if (Auth::user()->username !== 'adminbkpsdm') {
            return back()->withErrors(['error' => 'Akses Ditolak! Hanya Super Admin yang diizinkan mendaftarkan akun baru.']);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'nip' => 'required|string|unique:users,nip',
            'password' => 'required|string|min:6',
        ]);

        \App\Models\User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'nip' => $data['nip'],
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Admin baru berhasil didaftarkan ke sistem!');
    })->name('pengaturan.tambah_admin');

    // Proses Hapus Admin - LOCKDOWN: Khusus Super Admin (adminbkpsdm)
    Route::delete('/pengaturan/hapus-admin/{id}', function ($id) {
        // 1. Proteksi Tingkat Pertama: Pastikan yang menghapus adalah Super Admin utama
        if (Auth::user()->username !== 'adminbkpsdm') {
            return back()->withErrors(['error' => 'Akses Ditolak! Hanya Super Admin yang diizinkan menghapus akun admin lain.']);
        }

        // 2. Proteksi Tingkat Kedua: Mencegah Super Admin menghapus dirinya sendiri secara tidak sengaja
        if (Auth::id() == $id) {
            return back()->withErrors(['error' => 'Gagal! Anda tidak diizinkan menghapus akun Super Admin yang sedang aktif.']);
        }
        
        \App\Models\User::destroy($id);
        return back()->with('success', 'Akun admin berhasil dihapus secara permanen dari sistem!');
    })->name('pengaturan.hapus_admin');
});