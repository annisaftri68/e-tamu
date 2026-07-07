import { useEffect, useState } from "react";
import API from "../services/api";
import AdminLayout from "../layouts/AdminLayout";
import { 
  Calendar, 
  Search, 
  Plus, 
  Check, 
  X, 
  CheckCircle2, 
  Clock, 
  User, 
  Building, 
  Briefcase,
  AlertCircle
} from "lucide-react";

interface JadwalData {
  id: number;
  nama: string;
  instansi: string;
  bidang: string;
  keperluan: string;
  tanggal: string;
  jam: string;
  status: "Menunggu" | "Disetujui" | "Ditolak" | "Selesai";
}

export default function Jadwal() {
  const [loading, setLoading] = useState(true);
  const [jadwalList, setJadwalList] = useState<JadwalData[]>([]);
  
  // State Filter & Pencarian
  const [searchNama, setSearchNama] = useState("");
  const [filterTanggal, setFilterTanggal] = useState("");

  // State Modal Form Tambah
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [formData, setFormData] = useState({
    nama: "",
    instansi: "",
    bidang: "Sekretariat",
    keperluan: "",
    tanggal: "",
    jam: ""
  });

  useEffect(() => {
    fetchJadwal();
  }, []);

  // 1. Endpoint Ambil Data (GET)
  const fetchJadwal = async () => {
    try {
      const res = await API.get("/jadwal/get.php");
      if (res.data && Array.isArray(res.data)) {
        setJadwalList(res.data);
      } else {
        console.error("Respon API bukan array:", res.data);
        setJadwalList([]);
      }
    } catch (err) {
      console.error("Gagal memuat jadwal:", err);
      setJadwalList([]);
    } finally {
      setLoading(false);
    }
  };

  // 2. Endpoint Tambah Data (POST)
  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleCreateJadwal = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const res = await API.post("/jadwal/create.php", formData);
      if (res.data) {
        alert("Jadwal kunjungan berhasil ditambahkan!");
        setIsModalOpen(false);
        setFormData({ nama: "", instansi: "", bidang: "Sekretariat", keperluan: "", tanggal: "", jam: "" });
        fetchJadwal();
      }
    } catch (err) {
      console.error("Gagal menambahkan jadwal:", err);
      alert("Terjadi kesalahan saat menyimpan data.");
    }
  };

  // 3, 4, 5. Endpoint Update Status (Setujui, Tolak, Selesai)
  const handleUpdateStatus = async (id: number, statusBaru: "Disetujui" | "Ditolak" | "Selesai") => {
    let endpoint = "";
    if (statusBaru === "Disetujui") endpoint = "/jadwal/setujui.php";
    if (statusBaru === "Ditolak") endpoint = "/jadwal/tolak.php";
    if (statusBaru === "Selesai") endpoint = "/jadwal/selesai.php";

    try {
      const res = await API.post(endpoint, { id });
      if (res.data) {
        setJadwalList(prev => 
          Array.isArray(prev)
            ? prev.map(item => item.id === id ? { ...item, status: statusBaru } : item)
            : []
        );
      }
    } catch (err) {
      console.error(`Gagal mengubah status ke ${statusBaru}:`, err);
      alert("Gagal memperbarui status jadwal.");
    }
  };

  // Logika Filter Sisi Klien Berbasis Array Guard
  const filteredJadwal = Array.isArray(jadwalList)
    ? jadwalList.filter((item) => {
        const matchNama = item.nama?.toLowerCase().includes(searchNama.toLowerCase());
        const matchTanggal = filterTanggal === "" || item.tanggal === filterTanggal;
        return matchNama && matchTanggal;
      })
    : [];

  if (loading) {
    return (
      <AdminLayout>
        <div className="p-10 text-center text-slate-500 font-medium animate-pulse">
          Memuat agenda jadwal...
        </div>
      </AdminLayout>
    );
  }

  return (
    <AdminLayout>
      <div className="p-6 bg-slate-50 min-h-screen">
        
        {/* HEADER & TOMBOL TAMBAH */}
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
          <div>
            <div className="flex items-center gap-3">
              <div className="p-2 bg-blue-100 rounded-lg text-blue-700">
                <Calendar size={28} />
              </div>
              <h1 className="text-3xl font-bold text-slate-800 tracking-tight">
                Agenda Jadwal Kunjungan
              </h1>
            </div>
            <p className="text-gray-500 text-sm mt-1 ml-11">
              Persetujuan, manajemen waktu, dan verifikasi status kehadiran tamu
            </p>
          </div>

          <button
            onClick={() => setIsModalOpen(true)}
            className="flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow-md shadow-blue-600/20 self-start md:self-auto"
          >
            <Plus size={18} />
            Tambah Jadwal
          </button>
        </div>

        {/* UTILITY: CARI & FILTER TANGGAL */}
        <div className="bg-white rounded-2xl shadow-sm p-4 mb-6 border border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div className="relative">
            <Search size={18} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input
              type="text"
              placeholder="Cari nama tamu..."
              value={searchNama}
              onChange={(e) => setSearchNama(e.target.value)}
              className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:ring-2 focus:ring-blue-500 text-sm"
            />
          </div>

          <div className="relative">
            <Calendar size={18} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input
              type="date"
              value={filterTanggal}
              onChange={(e) => setFilterTanggal(e.target.value)}
              className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:ring-2 focus:ring-blue-500 text-sm text-gray-600"
            />
          </div>
        </div>

        {/* MODAL FORM TAMBAH JADWAL */}
        {isModalOpen && (
          <div className="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div className="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
              <div className="bg-slate-800 px-6 py-4 text-white flex justify-between items-center">
                <h3 className="font-bold text-lg">Form Tambah Jadwal Baru</h3>
                <button onClick={() => setIsModalOpen(false)} className="text-slate-400 hover:text-white transition">
                  <X size={20} />
                </button>
              </div>
              
              <form onSubmit={handleCreateJadwal} className="p-6 space-y-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-500 uppercase mb-1">Nama Tamu</label>
                  <input type="text" name="nama" required value={formData.nama} onChange={handleInputChange} className="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Masukkan nama lengkap" />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-500 uppercase mb-1">Instansi / Organisasi</label>
                  <input type="text" name="instansi" required value={formData.instansi} onChange={handleInputChange} className="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Nama instansi/sekolah/pribadi" />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-500 uppercase mb-1">Bidang Tujuan</label>
                  <select name="bidang" value={formData.bidang} onChange={handleInputChange} className="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <option value="Sekretariat">Sekretariat</option>
                    <option value="Mutasi">Bidang Mutasi</option>
                    <option value="Banglir">Bidang Banglir</option>
                    <option value="PPIK">Bidang PPIK</option>
                  </select>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-semibold text-slate-500 uppercase mb-1">Tanggal Kunjungan</label>
                    <input type="date" name="tanggal" required value={formData.tanggal} onChange={handleInputChange} className="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none" />
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-slate-500 uppercase mb-1">Jam Berkunjung</label>
                    <input type="time" name="jam" required value={formData.jam} onChange={handleInputChange} className="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none" />
                  </div>
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-500 uppercase mb-1">Maksud / Keperluan</label>
                  <textarea name="keperluan" rows={3} required value={formData.keperluan} onChange={handleInputChange} className="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none" placeholder="Tulis keperluan secara singkat dan jelas"></textarea>
                </div>
                
                <div className="flex gap-3 justify-end pt-2">
                  <button type="button" onClick={() => setIsModalOpen(false)} className="px-4 py-2.5 border border-slate-200 hover:bg-slate-50 rounded-xl text-sm font-medium text-slate-600 transition">Batal</button>
                  <button type="submit" className="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition">Simpan Jadwal</button>
                </div>
              </form>
            </div>
          </div>
        )}

        {/* MODERN GRID VIEW / TABLE */}
        <div className="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full border-collapse">
              <thead>
                <tr className="bg-slate-800 text-white text-sm">
                  <th className="px-6 py-4 text-left font-semibold">Tamu & Instansi</th>
                  <th className="px-6 py-4 text-left font-semibold">Tujuan & Keperluan</th>
                  <th className="px-6 py-4 text-left font-semibold">Waktu Pelaksanaan</th>
                  <th className="px-6 py-4 text-center font-semibold">Status</th>
                  <th className="px-6 py-4 text-center font-semibold">Aksi Manajemen</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {filteredJadwal.length > 0 ? (
                  filteredJadwal.map((item) => (
                    <tr key={item.id} className="hover:bg-slate-50/50 transition">
                      
                      {/* Tamu & Instansi */}
                      <td className="px-6 py-4">
                        <div className="flex flex-col">
                          <span className="font-semibold text-slate-800 text-base flex items-center gap-1.5">
                            <User size={14} className="text-slate-400" /> {item.nama}
                          </span>
                          <span className="text-xs text-slate-400 mt-0.5 flex items-center gap-1.5">
                            <Building size={12} /> {item.instansi}
                          </span>
                        </div>
                      </td>

                      {/* Tujuan & Keperluan */}
                      <td className="px-6 py-4 max-w-xs">
                        <div className="flex flex-col">
                          <span className="text-xs font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md self-start mb-1 flex items-center gap-1">
                            <Briefcase size={11} /> {item.bidang}
                          </span>
                          <span className="text-sm text-slate-600 line-clamp-2">
                            {item.keperluan}
                          </span>
                        </div>
                      </td>

                      {/* Waktu Pelaksanaan */}
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="flex flex-col text-sm text-slate-600 font-medium">
                          <span className="flex items-center gap-1.5">
                            <Calendar size={14} className="text-slate-400" /> {item.tanggal}
                          </span>
                          <span className="text-xs text-slate-400 mt-1 font-mono flex items-center gap-1.5">
                            <Clock size={12} /> {item.jam} WIB
                          </span>
                        </div>
                      </td>

                      {/* Status */}
                      <td className="px-6 py-4 text-center whitespace-nowrap">
                        <span className={`inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold ${
                          item.status === "Selesai" ? "bg-green-100 text-green-700" :
                          item.status === "Disetujui" ? "bg-blue-100 text-blue-700" :
                          item.status === "Ditolak" ? "bg-red-100 text-red-700" :
                          "bg-amber-100 text-amber-700 animate-pulse"
                        }`}>
                          {item.status === "Menunggu" && <Clock size={12} />}
                          {item.status === "Disetujui" && <Check size={12} />}
                          {item.status === "Ditolak" && <X size={12} />}
                          {item.status === "Selesai" && <CheckCircle2 size={12} />}
                          {item.status}
                        </span>
                      </td>

                      {/* Aksi Dinamis */}
                      <td className="px-6 py-4 text-center whitespace-nowrap">
                        <div className="flex items-center justify-center gap-2">
                          {item.status === "Menunggu" && (
                            <>
                              <button
                                onClick={() => handleUpdateStatus(item.id, "Disetujui")}
                                title="Setujui Kunjungan"
                                className="p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl transition shadow-sm border border-emerald-200"
                              >
                                <Check size={16} />
                              </button>
                              <button
                                onClick={() => handleUpdateStatus(item.id, "Ditolak")}
                                title="Tolak Kunjungan"
                                className="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition shadow-sm border border-red-200"
                              >
                                <X size={16} />
                              </button>
                            </>
                          )}

                          {item.status === "Disetujui" && (
                            <button
                              onClick={() => handleUpdateStatus(item.id, "Selesai")}
                              className="flex items-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-xs font-semibold transition shadow-sm"
                            >
                              <CheckCircle2 size={14} />
                              Selesai
                            </button>
                          )}

                          {(item.status === "Ditolak" || item.status === "Selesai") && (
                            <span className="text-xs text-slate-300 italic font-medium select-none">
                              Tidak ada aksi
                            </span>
                          )}
                        </div>
                      </td>

                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={5} className="text-center py-16 text-slate-400">
                      <div className="flex flex-col items-center justify-center gap-2">
                        <AlertCircle size={28} className="text-slate-300" />
                        <span className="text-sm">Tidak ditemukan jadwal kunjungan yang cocok.</span>
                      </div>
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </AdminLayout>
  );
}