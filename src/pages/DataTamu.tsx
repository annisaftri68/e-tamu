import { useEffect, useState } from "react";
import API from "../services/api";
import AdminLayout from "../layouts/AdminLayout";

import {
  Search,
  Edit2,
  Trash2,
  Eye,
  Filter,
  Users,
  Clock,
  CheckCircle,
} from "lucide-react";

interface Tamu {
  id: number;
  nama: string;
  nik: string;
  instansi: string;
  no_hp: string;
  keperluan: string;
  department_id: number;
  tujuan: string;
  tanggal: string;
  waktu: string;
  status: string;
}

const statusColor: Record<
  string,
  {
    bg: string;
    text: string;
  }
> = {
  Menunggu: {
    bg: "#fef3c7",
    text: "#d97706",
  },
  Proses: {
    bg: "#dbeafe",
    text: "#2563eb",
  },
  Selesai: {
    bg: "#dcfce7",
    text: "#16a34a",
  },
};

export default function DataTamu() {
  const [tamu, setTamu] = useState<Tamu[]>([]);
  const [search, setSearch] = useState("");
  const [filterStatus, setFilterStatus] = useState("Semua");
  const [viewTamu, setViewTamu] = useState<Tamu | null>(null);
  const [editTamu, setEditTamu] = useState<Tamu | null>(null);

  useEffect(() => {
    getVisitors();
  }, []);

  const getVisitors = async () => {
    try {
      const response = await API.get("/visitors/get.php");
      setTamu(response.data);
    } catch (error) {
      console.log("Gagal mengambil data tamu", error);
    }
  };

  const filtered = tamu.filter((t) => {
    const matchSearch =
      t.nama?.toLowerCase().includes(search.toLowerCase()) ||
      t.instansi?.toLowerCase().includes(search.toLowerCase());

    const matchStatus =
      filterStatus === "Semua" || t.status === filterStatus;

    return matchSearch && matchStatus;
  });

  // Fungsi untuk menyimpan perubahan data edit (Auto Refresh)
  const saveEdit = async () => {
    try {
      await API.post("/visitors/update.php", editTamu);
      alert("Data berhasil diupdate");
      setEditTamu(null);
      getVisitors(); // Me-refresh tabel secara realtime tanpa F5
    } catch (error) {
      console.log(error);
      alert("Gagal mengupdate data");
    }
  };

  // Fungsi menghapus data tamu (Auto Refresh jika sukses)
  const handleDelete = async (id: number) => {
    const konfirmasi = window.confirm(
      "Yakin ingin menghapus data tamu ini?"
    );

    if (!konfirmasi) return;

    try {
      const response = await API.post(
        "/visitors/delete.php",
        {
          id,
        }
      );

      if (response.data.success) {
        alert("Data berhasil dihapus");
        getVisitors(); // Me-refresh tabel secara realtime tanpa F5
      } else {
        alert(response.data.message);
      }
    } catch (err) {
      console.log(err);
      alert("Gagal menghapus data");
    }
  };

  return (
    <AdminLayout>
      <div className="p-6 bg-slate-50 min-h-screen">
        {/* HEADER */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-slate-800">Data Tamu</h1>
          <p className="text-gray-500 mt-2">
            Kelola seluruh data kunjungan tamu BKPSDM Kabupaten Tegal
          </p>
        </div>

        {/* CARD STATISTIK */}
        <div className="grid md:grid-cols-3 gap-5 mb-8">
          <div className="bg-white rounded-2xl shadow-sm p-6">
            <div className="flex justify-between items-center">
              <div>
                <p className="text-gray-500 text-sm">Total Tamu</p>
                <h2 className="text-3xl font-bold mt-2">{tamu.length}</h2>
              </div>
              <div className="bg-blue-500 p-4 rounded-xl">
                <Users className="text-white" size={28} />
              </div>
            </div>
          </div>

          <div className="bg-white rounded-2xl shadow-sm p-6">
            <div className="flex justify-between items-center">
              <div>
                <p className="text-gray-500 text-sm">Menunggu</p>
                <h2 className="text-3xl font-bold mt-2">
                  {tamu.filter((t) => t.status === "Menunggu").length}
                </h2>
              </div>
              <div className="bg-yellow-500 p-4 rounded-xl">
                <Clock className="text-white" size={28} />
              </div>
            </div>
          </div>

          <div className="bg-white rounded-2xl shadow-sm p-6">
            <div className="flex justify-between items-center">
              <div>
                <p className="text-gray-500 text-sm">Selesai</p>
                <h2 className="text-3xl font-bold mt-2">
                  {tamu.filter((t) => t.status === "Selesai").length}
                </h2>
              </div>
              <div className="bg-green-500 p-4 rounded-xl">
                <CheckCircle className="text-white" size={28} />
              </div>
            </div>
          </div>
        </div>

        {/* SEARCH & FILTER */}
        <div className="bg-white rounded-2xl shadow-sm p-5 mb-6 border flex flex-wrap gap-4 items-center">
          <div className="relative flex-1 min-w-[250px]">
            <Search
              size={18}
              className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
            />
            <input
              type="text"
              placeholder="Cari nama atau instansi..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="w-full pl-10 pr-4 py-3 rounded-xl border bg-slate-50 outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <div className="flex gap-2 flex-wrap">
            <Filter className="text-gray-500 mt-2" size={18} />
            {["Semua", "Menunggu", "Proses", "Selesai"].map((item) => (
              <button
                key={item}
                onClick={() => setFilterStatus(item)}
                className="px-4 py-2 rounded-xl transition"
                style={{
                  background: filterStatus === item ? "#2563eb" : "#f3f4f6",
                  color: filterStatus === item ? "#fff" : "#6b7280",
                }}
              >
                {item}
              </button>
            ))}
          </div>
        </div>

        {/* TABLE */}
        <div className="bg-white rounded-2xl shadow-sm overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead className="bg-blue-700">
                <tr>
                  {[
                    "No",
                    "Nama",
                    "NIK",
                    "Instansi",
                    "Keperluan",
                    "Tujuan",
                    "Tanggal",
                    "Waktu",
                    "Status",
                    "Aksi",
                  ].map((item) => (
                    <th key={item} className="px-5 py-4 text-left text-white">
                      {item}
                    </th>
                  ))}
                </tr>
              </thead>

              <tbody>
                {filtered.length > 0 ? (
                  filtered.map((t, index) => (
                    <tr
                      key={t.id}
                      className="border-b hover:bg-slate-50 transition"
                    >
                      <td className="px-5 py-4">{index + 1}</td>
                      <td className="px-5 py-4 font-medium">{t.nama}</td>
                      <td className="px-5 py-4">{t.nik}</td>
                      <td className="px-5 py-4">{t.instansi}</td>
                      <td className="px-5 py-4">{t.keperluan}</td>
                      <td className="px-5 py-4">{t.tujuan}</td>
                      <td className="px-5 py-4">{t.tanggal}</td>
                      <td className="px-5 py-4">{t.waktu}</td>
                      <td className="px-5 py-4">
                        <span
                          className="px-3 py-1 rounded-full text-sm font-medium"
                          style={{
                            background: statusColor[t.status]?.bg,
                            color: statusColor[t.status]?.text,
                          }}
                        >
                          {t.status}
                        </span>
                      </td>

                      <td className="px-5 py-4">
                        <div className="flex gap-2">
                          {/* Tombol Lihat Detail */}
                          <button
                            onClick={() => setViewTamu({ ...t })}
                            className="p-2 rounded-lg text-blue-600 hover:bg-blue-100"
                          >
                            <Eye size={18} />
                          </button>

                          {/* Tombol Pensil (Edit) */}
                          <button
                            onClick={() => setEditTamu({ ...t })}
                            className="p-2 rounded-lg text-yellow-600 hover:bg-yellow-100"
                          >
                            <Edit2 size={18} />
                          </button>

                          {/* Tombol Hapus Baru */}
                          <button
                            onClick={() => handleDelete(t.id)}
                            className="p-2 rounded-lg text-red-600 hover:bg-red-100"
                          >
                            <Trash2 size={18} />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={10} className="text-center py-10 text-gray-500">
                      Tidak ada data tamu
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>

          <div className="px-5 py-4 border-t flex justify-between text-sm text-gray-500">
            <span>
              Menampilkan {filtered.length} dari {tamu.length} data
            </span>
            <span>BKPSDM Kabupaten Tegal</span>
          </div>
        </div>

        {/* MODAL DETAIL */}
        {viewTamu && (
          <div
            className="fixed inset-0 z-50 flex items-center justify-center"
            style={{ background: "rgba(0,0,0,0.5)" }}
          >
            <div className="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden">
              <div className="bg-blue-700 p-5">
                <h3 className="text-white text-xl font-semibold">
                  Detail Tamu
                </h3>
              </div>

              <div className="p-6 space-y-4">
                {[
                  ["Nama", viewTamu.nama],
                  ["NIK", viewTamu.nik],
                  ["Instansi", viewTamu.instansi],
                  ["Keperluan", viewTamu.keperluan],
                  ["Tujuan", viewTamu.tujuan],
                  ["No HP", viewTamu.no_hp],
                ].map(([k, v]) => (
                  <div key={k} className="flex justify-between border-b pb-2">
                    <span className="text-gray-500">{k}</span>
                    <span className="font-medium">{v}</span>
                  </div>
                ))}

                <button
                  onClick={() => setViewTamu(null)}
                  className="w-full bg-blue-700 text-white py-3 rounded-xl hover:bg-blue-800 transition"
                >
                  Tutup
                </button>
              </div>
            </div>
          </div>
        )}

        {/* MODAL EDIT */}
        {editTamu && (
          <div
            className="fixed inset-0 z-50 flex items-center justify-center"
            style={{ background: "rgba(0,0,0,.5)" }}
          >
            <div className="bg-white rounded-3xl w-full max-w-xl p-7 shadow-2xl">
              <h2 className="text-2xl font-bold mb-6">Edit Data Tamu</h2>

              <label className="block text-sm font-medium text-gray-600 mb-1">Nama</label>
              <input
                className="w-full border rounded-xl p-3 mb-3 outline-none focus:border-blue-500"
                value={editTamu.nama}
                onChange={(e) => setEditTamu({ ...editTamu, nama: e.target.value })}
              />

              <label className="block text-sm font-medium text-gray-600 mb-1">NIK</label>
              <input
                className="w-full border rounded-xl p-3 mb-3 outline-none focus:border-blue-500"
                value={editTamu.nik}
                onChange={(e) => setEditTamu({ ...editTamu, nik: e.target.value })}
              />

              <label className="block text-sm font-medium text-gray-600 mb-1">Instansi</label>
              <input
                className="w-full border rounded-xl p-3 mb-3 outline-none focus:border-blue-500"
                value={editTamu.instansi}
                onChange={(e) => setEditTamu({ ...editTamu, instansi: e.target.value })}
              />

              <label className="block text-sm font-medium text-gray-600 mb-1">No HP</label>
              <input
                className="w-full border rounded-xl p-3 mb-3 outline-none focus:border-blue-500"
                value={editTamu.no_hp}
                onChange={(e) => setEditTamu({ ...editTamu, no_hp: e.target.value })}
              />

              <label className="block text-sm font-medium text-gray-600 mb-1">Keperluan</label>
              <textarea
                className="w-full border rounded-xl p-3 mb-3 outline-none focus:border-blue-500 h-24 resize-none"
                value={editTamu.keperluan}
                onChange={(e) => setEditTamu({ ...editTamu, keperluan: e.target.value })}
              />

              <label className="block text-sm font-medium text-gray-600 mb-1">Status</label>
              <select
                className="w-full border rounded-xl p-3 mb-6 bg-white outline-none focus:border-blue-500"
                value={editTamu.status}
                onChange={(e) => setEditTamu({ ...editTamu, status: e.target.value })}
              >
                <option value="Menunggu">Menunggu</option>
                <option value="Proses">Proses</option>
                <option value="Selesai">Selesai</option>
              </select>

              <div className="flex justify-end gap-3">
                <button
                  onClick={() => setEditTamu(null)}
                  className="px-5 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 transition"
                >
                  Batal
                </button>
                <button
                  onClick={saveEdit}
                  className="px-5 py-2 rounded-xl bg-blue-700 text-white hover:bg-blue-800 transition"
                >
                  Simpan
                </button>
              </div>
            </div>
          </div>
        )}
      </div>
    </AdminLayout>
  );
}