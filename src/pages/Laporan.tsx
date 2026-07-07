import { useEffect, useState } from "react";
import API from "../services/api";
import AdminLayout from "../layouts/AdminLayout";
import {
  FileText,
  Search,
  Calendar,
  Download,
  Printer,
  ChevronDown,
  Filter,
} from "lucide-react";

interface LaporanTamu {
  id: number;
  nama: string;
  instansi: string;
  tujuan: string; // Mapping dari 'bidang' atau 'tujuan'
  bidang?: string;
  status: string;
  tanggal: string;
  waktu: string;  // Mapping dari 'jam' atau 'waktu'
  jam?: string;
}

export default function Laporan() {
  const [loading, setLoading] = useState(true);
  const [laporan, setLaporan] = useState<LaporanTamu[]>([]);
  
  // State Ringkasan (Summary)
  const [summary, setSummary] = useState({
    total: 0,
    hariIni: 0,
    bulanIni: 0,
    tahunIni: 0,
  });

  // State Filter
  const [searchNama, setSearchNama] = useState("");
  const [filterStatus, setFilterStatus] = useState("Semua");
  const [tanggalAwal, setTanggalAwal] = useState("");
  const [tanggalAkhir, setTanggalAkhir] = useState("");

  useEffect(() => {
    fetchLaporanData();
  }, []);

  const fetchLaporanData = async () => {
    try {
      const [laporanRes, summaryRes] = await Promise.all([
        API.get("/laporan/get.php"),
        API.get("/laporan/summary.php"), 
      ]);

      if (laporanRes.data && Array.isArray(laporanRes.data)) {
        setLaporan(laporanRes.data);
      } else {
        setLaporan([]);
      }
      
      setSummary(summaryRes.data || { total: 0, hariIni: 0, bulanIni: 0, tahunIni: 0 });
    } catch (err) {
      console.error("Gagal memuat data laporan:", err);
      setLaporan([]);
    } finally {
      setLoading(false);
    }
  };

  // Logika Pemfilteran Data Sisi Klien Berbasis Array Guard
  const filteredData = Array.isArray(laporan)
    ? laporan.filter((item) => {
        const matchNama = item.nama?.toLowerCase().includes(searchNama.toLowerCase());
        const matchStatus = filterStatus === "Semua" || item.status === filterStatus;
        
        // Filter Berdasarkan Rentang Tanggal
        let matchTanggal = true;
        if (tanggalAwal && tanggalAkhir) {
          matchTanggal = item.tanggal >= tanggalAwal && item.tanggal <= tanggalAkhir;
        } else if (tanggalAwal) {
          matchTanggal = item.tanggal >= tanggalAwal;
        } else if (tanggalAkhir) {
          matchTanggal = item.tanggal <= tanggalAkhir;
        }

        return matchNama && matchStatus && matchTanggal;
      })
    : [];

  const handleExportExcel = () => {
    alert("Mengekspor data ke Excel...");
  };

  const handleExportPDF = () => {
    alert("Mengunduh dokumen PDF...");
  };

  const handlePrint = () => {
    window.print();
  };

  if (loading) {
    return (
      <AdminLayout>
        <div className="p-10 text-center text-slate-500 font-medium animate-pulse">
          Memuat data laporan...
        </div>
      </AdminLayout>
    );
  }

  return (
    <AdminLayout>
      <div className="p-6 bg-slate-50 min-h-screen no-print">
        
        {/* HEADER */}
        <div className="mb-8">
          <div className="flex items-center gap-3">
            <div className="p-2 bg-blue-100 rounded-lg text-blue-700">
              <FileText size={28} />
            </div>
            <div>
              <h1 className="text-3xl font-bold text-slate-800 tracking-tight">
                LAPORAN KUNJUNGAN TAMU
              </h1>
              <p className="text-gray-500 text-sm mt-1">
                Kelola, cetak dan arsip seluruh data kunjungan
              </p>
            </div>
          </div>
        </div>

        {/* 4 CARD RINGKASAN */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
          <div className="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p className="text-gray-400 text-xs font-semibold uppercase tracking-wider">Total</p>
            <h3 className="text-3xl font-bold text-slate-800 mt-2">{summary.total}</h3>
          </div>
          
          <div className="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p className="text-gray-400 text-xs font-semibold uppercase tracking-wider">Hari Ini</p>
            <h3 className="text-3xl font-bold text-blue-600 mt-2">{summary.hariIni}</h3>
          </div>

          <div className="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p className="text-gray-400 text-xs font-semibold uppercase tracking-wider">Bulan Ini</p>
            <h3 className="text-3xl font-bold text-emerald-600 mt-2">{summary.bulanIni}</h3>
          </div>

          <div className="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p className="text-gray-400 text-xs font-semibold uppercase tracking-wider">Tahun Ini</p>
            <h3 className="text-3xl font-bold text-purple-600 mt-2">{summary.tahunIni}</h3>
          </div>
        </div>

        {/* UTILITAS & FILTER */}
        <div className="bg-white rounded-2xl shadow-sm p-6 mb-6 border border-slate-100 space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            {/* Cari Nama */}
            <div className="relative">
              <Search size={18} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input
                type="text"
                placeholder="Cari Nama..."
                value={searchNama}
                onChange={(e) => setSearchNama(e.target.value)}
                className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:ring-2 focus:ring-blue-500 text-sm"
              />
            </div>

            {/* Dropdown Status */}
            <div className="relative">
              <Filter size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <select
                value={filterStatus}
                onChange={(e) => setFilterStatus(e.target.value)}
                className="w-full pl-9 pr-8 py-2.5 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:ring-2 focus:ring-blue-500 text-sm appearance-none cursor-pointer"
              >
                <option value="Semua">Status: Semua</option>
                <option value="Menunggu">Menunggu</option>
                <option value="Disetujui">Disetujui</option>
                <option value="Ditolak">Ditolak</option>
                <option value="Selesai">Selesai</option>
              </select>
              <ChevronDown size={16} className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" />
            </div>

            {/* Tanggal Awal */}
            <div className="relative">
              <Calendar size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input
                type="date"
                value={tanggalAwal}
                onChange={(e) => setTanggalAwal(e.target.value)}
                className="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:ring-2 focus:ring-blue-500 text-sm text-gray-600"
              />
            </div>

            {/* Tanggal Akhir */}
            <div className="relative">
              <Calendar size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input
                type="date"
                value={tanggalAkhir}
                onChange={(e) => setTanggalAkhir(e.target.value)}
                className="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:ring-2 focus:ring-blue-500 text-sm text-gray-600"
              />
            </div>
          </div>

          <hr className="border-slate-100" />

          {/* TOMBOL AKSI EKSPOR */}
          <div className="flex flex-wrap gap-3 justify-end">
            <button
              onClick={handleExportExcel}
              className="flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-medium transition shadow-sm shadow-emerald-600/10"
            >
              <Download size={16} />
              Export Excel
            </button>
            
            <button
              onClick={handleExportPDF}
              className="flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-medium transition shadow-sm shadow-red-600/10"
            >
              <Download size={16} />
              Export PDF
            </button>

            <button
              onClick={handlePrint}
              className="flex items-center gap-2 px-4 py-2.5 bg-slate-700 hover:bg-slate-800 text-white rounded-xl text-sm font-medium transition shadow-sm"
            >
              <Printer size={16} />
              Print
            </button>
          </div>
        </div>

        {/* TABEL LAPORAN */}
        <div className="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100">
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead className="bg-slate-800 text-white">
                <tr>
                  <th className="px-5 py-3.5 text-left text-sm font-semibold">No</th>
                  <th className="px-5 py-3.5 text-left text-sm font-semibold">Nama</th>
                  <th className="px-5 py-3.5 text-left text-sm font-semibold">Instansi</th>
                  <th className="px-5 py-3.5 text-left text-sm font-semibold">Bidang</th>
                  <th className="px-5 py-3.5 text-left text-sm font-semibold">Status</th>
                  <th className="px-5 py-3.5 text-left text-sm font-semibold">Tanggal</th>
                  <th className="px-5 py-3.5 text-left text-sm font-semibold">Jam</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {filteredData.length > 0 ? (
                  filteredData.map((item, index) => (
                    <tr key={item.id} className="hover:bg-slate-50/80 transition text-slate-700 text-sm">
                      <td className="px-5 py-4 font-medium text-slate-400">{index + 1}</td>
                      <td className="px-5 py-4 font-semibold text-slate-800">{item.nama}</td>
                      <td className="px-5 py-4">{item.instansi}</td>
                      {/* Handle sinkronisasi properti 'bidang' vs 'tujuan' */}
                      <td className="px-5 py-4">{item.bidang || item.tujuan}</td>
                      <td className="px-5 py-4">
                        <span
                          className={`px-2.5 py-1 rounded-full text-xs font-semibold ${
                            item.status === "Selesai" ? "bg-green-100 text-green-700" :
                            item.status === "Disetujui" ? "bg-blue-100 text-blue-700" :
                            item.status === "Ditolak" ? "bg-red-100 text-red-700" :
                            "bg-amber-100 text-amber-700"
                          }`}
                        >
                          {item.status}
                        </span>
                      </td>
                      <td className="px-5 py-4">{item.tanggal}</td>
                      {/* Handle sinkronisasi properti 'jam' vs 'waktu' */}
                      <td className="px-5 py-4 font-mono text-slate-500">{item.jam || item.waktu} WIB</td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={7} className="text-center py-12 text-gray-400 text-sm">
                      Tidak ada data kunjungan yang cocok dengan kriteria filter.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
          
          {/* FOOTER TABEL */}
          <div className="px-5 py-4 bg-slate-50 border-t flex justify-between text-xs text-slate-400 font-medium">
            <span>Menampilkan {filteredData.length} baris data</span>
            <span>Arsip BKPSDM Kabupaten Tegal</span>
          </div>
        </div>

      </div>
    </AdminLayout>
  );
}