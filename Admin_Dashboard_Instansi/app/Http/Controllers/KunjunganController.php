<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamu;
use Carbon\Carbon;

class KunjunganController extends Controller
{
    public function index()
    {
        // KUNCI AUTO-RESET: Hanya mengambil data yang dibuat 'HARI INI'
        $tamus = Tamu::whereDate('created_at', Carbon::today())->latest()->get();

        // Menghitung statistik live khusus hari ini saja
        $totalAntrian     = Tamu::whereDate('created_at', Carbon::today())->count();
        $antrianSelesai   = Tamu::whereDate('created_at', Carbon::today())->where('status', 'Selesai')->count();
        $antrianDiproses  = Tamu::whereDate('created_at', Carbon::today())->where('status', 'Diproses')->count();
        $antrianMenunggu  = Tamu::whereDate('created_at', Carbon::today())->where('status', 'Menunggu')->count();

        return view('kunjungan-hari-ini', compact(
            'tamu', 
            'totalAntrian', 
            'antrianSelesai', 
            'antrianDiproses', 
            'antrianMenunggu'
        ));
    }
}