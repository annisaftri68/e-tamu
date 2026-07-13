<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use App\Models\TamuArsip;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan utama
     */
    public function index(Request $request)
    {
        Tamu::archiveMonthly();

        $query = Tamu::query();

        if ($request->filled('cari')) {
            $query->where(function ($sub) use ($request) {
                $sub->where('nama', 'like', '%'.$request->cari.'%')
                    ->orWhere('instansi', 'like', '%'.$request->cari.'%')
                    ->orWhere('tujuan', 'like', '%'.$request->cari.'%')
                    ->orWhere('bidang', 'like', '%'.$request->cari.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('created_at', [$request->tgl_mulai.' 00:00:00', $request->tgl_selesai.' 23:59:59']);
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
        $query = $this->buildLaporanQuery($request);
        
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
        $filename = "laporan_data_tamu_" . date('Y-m-d') . ".xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo $this->buildMonthlyExcelTables($request);
        exit;
    }

    public function buildMonthlyExcelTables(Request $request, $dataTamu = null): string
    {
        $activeRecords = $this->buildLaporanQuery($request, Tamu::class)->latest()->get();
        $archiveRecords = $this->buildLaporanQuery($request, TamuArsip::class)->latest()->get();

        $dataTamu = $activeRecords->concat($archiveRecords)
            ->sortByDesc(fn ($tamu) => $tamu->created_at ? $tamu->created_at->timestamp : 0)
            ->values();

        $groups = $dataTamu->groupBy(function ($tamu) {
            return $tamu->created_at?->translatedFormat('F Y') ?? 'Tanpa Bulan';
        });

        $html = '';

        foreach ($groups as $bulan => $records) {
            $html .= "<h3 style='margin: 16px 0 8px; font-size: 14px;'>Bulan: {$bulan}</h3>";
            $html .= "<table border='1' style='margin-bottom: 12px;'>";
            $html .= "<thead>
                    <tr>
                        <th style='background-color: #4F81BD; color: white;'>No</th>
                        <th style='background-color: #4F81BD; color: white;'>Nama Tamu</th>
                        <th style='background-color: #4F81BD; color: white;'>Instansi/Perusahaan</th>
                        <th style='background-color: #4F81BD; color: white;'>Keperluan</th>
                        <th style='background-color: #4F81BD; color: white;'>Tanggal Kunjungan</th>
                    </tr>
                  </thead>";
            $html .= "<tbody>";

            foreach ($records as $index => $tamu) {
                $html .= "<tr>";
                $html .= "<td>" . ($index + 1) . "</td>";
                $html .= "<td>" . e($tamu->nama_lengkap ?? $tamu->nama ?? '-') . "</td>";
                $html .= "<td>" . e($tamu->instansi ?? '-') . "</td>";
                $html .= "<td>" . e($tamu->keperluan ?? '-') . "</td>";
                $html .= "<td>" . ($tamu->created_at ? $tamu->created_at->format('d-m-Y H:i') : '-') . "</td>";
                $html .= "</tr>";
            }

            $html .= "</tbody>";
            $html .= "</table>";
        }

        return $html;
    }

    /**
     * Halaman untuk print browser
     */
    public function print(Request $request)
    {
        $query = $this->buildLaporanQuery($request);
        $tamus = $query->latest()->get();

        return view('laporan-print', compact('tamus'));
    }

    protected function buildLaporanQuery(Request $request, string $modelClass = Tamu::class)
    {
        $query = $modelClass::query();

        if ($request->filled('cari')) {
            $query->where(function ($sub) use ($request) {
                $sub->where('nama_lengkap', 'like', '%'.$request->cari.'%')
                    ->orWhere('nama', 'like', '%'.$request->cari.'%')
                    ->orWhere('instansi', 'like', '%'.$request->cari.'%')
                    ->orWhere('bidang', 'like', '%'.$request->cari.'%')
                    ->orWhere('keperluan', 'like', '%'.$request->cari.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('created_at', [$request->tgl_mulai.' 00:00:00', $request->tgl_selesai.' 23:59:59']);
        }

        return $query;
    }
}
