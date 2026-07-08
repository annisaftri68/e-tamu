<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamu; // Pastikan nama Model database Tamu Anda sudah benar
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan Halaman Dashboard Utama Admin
     */
    public function index()
    {
        // 1. Mengambil data riwayat 5 kunjungan tamu terbaru dari database
        $tamus = Tamu::latest()->take(5)->get();

        // 2. Menghitung angka indikator untuk kartu statistik (Mini Stats Card)
        $totalTamu = Tamu::count();
        
        // Menghitung status antrian hari ini (berdasarkan kolom status di database Anda)
        // Jika kolom status Anda menggunakan penamaan lain, silakan sesuaikan string nilainya
        $tamuMenunggu = Tamu::whereDate('created_at', Carbon::today())
                            ->where('status', 'Menunggu')
                            ->count();
                            
        $tamuSelesai = Tamu::whereDate('created_at', Carbon::today())
                           ->where('status', 'Selesai')
                           ->count();

        // 3. Mengolah Data Grafik Ikhtisar Kunjungan (7 Hari Terakhir)
        $grafikLabels = [];
        $grafikData = [];

        // Melakukan perulangan mundur 7 hari ke belakang untuk mengumpulkan statistik harian
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);
            
            // Mengambil nama hari dalam Bahasa Indonesia (Contoh: Sen, Sel, Rab)
            $grafikLabels[] = $tanggal->translatedFormat('D');
            
            // Hitung jumlah tamu yang datang pada tanggal tersebut di database
            $jumlahTamuHariItu = Tamu::whereDate('created_at', $tanggal)->count();
            $grafikData[] = $jumlahTamuHariItu;
        }

        // 4. Melempar seluruh variabel data ke dalam file View Dashboard Blade
        return view('dashboard', compact(
            'tamus', 
            'totalTamu', 
            'tamuMenunggu', 
            'tamuSelesai', 
            'grafikLabels', 
            'grafikData'
        ));
    }
}