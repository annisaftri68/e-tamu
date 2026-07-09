<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tamu; // Model Tamu yang benar di project ini
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan sudah install barryvdh/laravel-dompdf

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan utama
     */
    public function index(Request $request)
    {
        $query = Tamu::query();

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('created_at', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        $tamus = $query->latest()->paginate(15);

        $totalTamu = Tamu::count();
        $tamuHariIni = Tamu::whereDate('created_at', now())->count();
        $tamuBulanIni = Tamu::whereMonth('created_at', now())->whereYear('created_at', now())->count();
        $tamuTahunIni = Tamu::whereYear('created_at', now())->count();

        return view('laporan', compact('tamus', 'totalTamu', 'tamuHariIni', 'tamuBulanIni', 'tamuTahunIni'));
    }

    /**
     * Export ke PDF menggunakan DomPDF
     */
    public function exportPdf(Request $request)
    {
        $query = Tamu::query();
        
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('created_at', [$request->tgl_mulai, $request->tgl_selesai]);
        }
        
        $dataTamu = $query->latest()->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('dataTamu'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan_data_tamu_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export ke Excel tanpa library berat (Metode Native HTML Table Header)
     */
    public function exportExcel(Request $request)
    {
        $query = Tamu::query();
        
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('created_at', [$request->tgl_mulai, $request->tgl_selesai]);
        }
        
        $dataTamu = $query->latest()->get();

        $filename = "laporan_data_tamu_" . date('Y-m-d') . ".xls";

        // Mengatur header agar browser mendownloadnya sebagai file Excel (.xls)
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Membuat output berupa tabel HTML yang otomatis dibaca rapi oleh Microsoft Excel
        echo "<table border='1'>";
        echo "<thead>
                <tr>
                    <th style='background-color: #4F81BD; color: white;'>No</th>
                    <th style='background-color: #4F81BD; color: white;'>Nama Tamu</th>
                    <th style='background-color: #4F81BD; color: white;'>Instansi/Perusahaan</th>
                    <th style='background-color: #4F81BD; color: white;'>Keperluan</th>
                    <th style='background-color: #4F81BD; color: white;'>Tanggal Kunjungan</th>
                </tr>
              </thead>";
        echo "<tbody>";
        
        foreach ($dataTamu as $index => $tamu) {
            echo "<tr>";
            echo "<td>" . ($index + 1) . "</td>";
            echo "<td>" . e($tamu->nama) . "</td>"; // Sesuaikan properti kolom model Anda
            echo "<td>" . e($tamu->instansi) . "</td>";
            echo "<td>" . e($tamu->keperluan) . "</td>";
            echo "<td>" . $tamu->created_at->format('d-m-Y H:i') . "</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
        exit;
    }
}
