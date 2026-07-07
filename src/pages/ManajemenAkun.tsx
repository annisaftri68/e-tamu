import { useState } from "react";
import { PEGAWAI_DATA } from "../data/pegawai";
import { Plus, Edit2, Trash2, Search, ShieldCheck, Eye, EyeOff, X } from "lucide-react";

type Pegawai = typeof PEGAWAI_DATA[0];

const roleBadge: Record<string, { bg: string; text: string; border: string }> = {
  "Super Admin": { bg: "#fef3c7", text: "#92400e", border: "#fcd34d" },
  "Admin": { bg: "#dbeafe", text: "#1e40af", border: "#93c5fd" },
  "Operator": { bg: "#dcfce7", text: "#166534", border: "#86efac" },
  "Viewer": { bg: "#f3f4f6", text: "#4b5563", border: "#d1d5db" },
};

const unitList = ["Pimpinan", "IT & Sistem", "Pelayanan Umum", "Bidang Mutasi", "Bidang Kepangkatan", "Bidang Pengembangan", "Sekretariat"];

export function ManajemenAkun() {
  const [pegawai, setPegawai] = useState<Pegawai[]>(PEGAWAI_DATA);
  const [search, setSearch] = useState("");
  const [filterRole, setFilterRole] = useState("Semua");
  const [showModal, setShowModal] = useState(false);
  const [editData, setEditData] = useState<Pegawai | null>(null);
  const [showPass, setShowPass] = useState(false);
  const [deleteId, setDeleteId] = useState<string | null>(null);

  const [form, setForm] = useState<Omit<Pegawai, "nip"> & { nip: string }>({
    nip: "", nama: "", jabatan: "", role: "Operator", unit: "Pelayanan Umum", password: "",
  });

  const filtered = pegawai.filter((p) => {
    const q = search.toLowerCase();
    const matchSearch = p.nama.toLowerCase().includes(q) || p.nip.includes(q) || p.unit.toLowerCase().includes(q);
    const matchRole = filterRole === "Semua" || p.role === filterRole;
    return matchSearch && matchRole;
  });

  const openAdd = () => {
    setEditData(null);
    setForm({ nip: "", nama: "", jabatan: "", role: "Operator", unit: "Pelayanan Umum", password: "" });
    setShowModal(true);
  };

  const openEdit = (p: Pegawai) => {
    setEditData(p);
    setForm({ ...p });
    setShowModal(true);
  };

  const handleSave = () => {
    if (!form.nip || !form.nama || !form.password) return;
    if (editData) {
      setPegawai((prev) => prev.map((p) => p.nip === editData.nip ? { ...form } : p));
    } else {
      setPegawai((prev) => [...prev, { ...form }]);
    }
    setShowModal(false);
  };

  const handleDelete = (nip: string) => {
    setPegawai((prev) => prev.filter((p) => p.nip !== nip));
    setDeleteId(null);
  };

  const roleCounts = ["Super Admin", "Admin", "Operator", "Viewer"].map((r) => ({
    role: r, count: pegawai.filter((p) => p.role === r).length,
  }));

  return (
    <div className="p-6 h-full overflow-y-auto">
      {/* Header */}
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-gray-800" style={{ fontWeight: 700 }}>Manajemen Akun</h1>
          <p className="text-gray-500" style={{ fontSize: "0.82rem" }}>Kelola akun login pegawai BKPSDM</p>
        </div>
        <button onClick={openAdd}
          className="flex items-center gap-2 px-4 py-2 rounded-xl text-white"
          style={{ background: "linear-gradient(135deg, #1e40af, #2563eb)", fontSize: "0.82rem", fontWeight: 600 }}>
          <Plus size={16} /> Tambah Akun
        </button>
      </div>

      {/* Role Summary */}
      <div className="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
        {roleCounts.map((r) => {
          const rb = roleBadge[r.role];
          return (
            <div key={r.role} className="bg-white rounded-2xl p-4 shadow-sm border flex items-center gap-3"
              style={{ borderColor: "#e5e7eb", borderLeft: `4px solid ${rb.border}` }}>
              <div className="w-10 h-10 rounded-xl flex items-center justify-center" style={{ background: rb.bg }}>
                <ShieldCheck size={18} style={{ color: rb.text }} />
              </div>
              <div>
                <p className="text-gray-500" style={{ fontSize: "0.7rem" }}>{r.role}</p>
                <p className="text-gray-800" style={{ fontSize: "1.4rem", fontWeight: 700, lineHeight: 1 }}>{r.count}</p>
              </div>
            </div>
          );
        })}
      </div>

      {/* Filter Bar */}
      <div className="bg-white rounded-2xl p-4 mb-4 shadow-sm border flex flex-wrap gap-3 items-center"
        style={{ borderColor: "#e5e7eb" }}>
        <div className="relative flex-1 min-w-48">
          <Search size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
          <input value={search} onChange={(e) => setSearch(e.target.value)}
            placeholder="Cari nama, NIP, atau unit..."
            className="w-full pl-9 pr-4 py-2 rounded-lg border outline-none text-gray-700"
            style={{ fontSize: "0.82rem", borderColor: "#e5e7eb", background: "#f9fafb" }} />
        </div>
        <div className="flex items-center gap-2 flex-wrap">
          {["Semua", "Super Admin", "Admin", "Operator", "Viewer"].map((r) => (
            <button key={r} onClick={() => setFilterRole(r)}
              className="px-3 py-1.5 rounded-lg transition-all"
              style={{
                fontSize: "0.73rem", fontWeight: 500,
                background: filterRole === r ? "#2563eb" : "#f3f4f6",
                color: filterRole === r ? "#fff" : "#6b7280",
              }}>{r}</button>
          ))}
        </div>
      </div>

      {/* Table */}
      <div className="bg-white rounded-2xl shadow-sm border overflow-hidden" style={{ borderColor: "#e5e7eb" }}>
        <div className="overflow-x-auto">
          <table className="w-full" style={{ fontSize: "0.8rem" }}>
            <thead style={{ background: "linear-gradient(135deg, #1e40af, #2563eb)" }}>
              <tr>
                {["No", "Nama Pegawai", "NIP", "Jabatan", "Unit Kerja", "Password", "Role", "Status", "Aksi"].map((h) => (
                  <th key={h} className="text-left px-4 py-3 text-white" style={{ fontWeight: 600, whiteSpace: "nowrap" }}>{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {filtered.map((p, i) => {
                const rb = roleBadge[p.role];
                return (
                  <tr key={p.nip} style={{ borderBottom: "1px solid #f3f4f6", background: i % 2 === 0 ? "#fff" : "#fafafa" }}>
                    <td className="px-4 py-3 text-gray-400">{i + 1}</td>
                    <td className="px-4 py-3">
                      <div className="flex items-center gap-2">
                        <div className="w-8 h-8 rounded-lg flex items-center justify-center text-white flex-shrink-0"
                          style={{ background: "linear-gradient(135deg, #1e40af, #3b82f6)", fontSize: "0.75rem", fontWeight: 700 }}>
                          {p.nama.charAt(0)}
                        </div>
                        <div>
                          <p className="text-gray-800" style={{ fontWeight: 600 }}>{p.nama}</p>
                          <p className="text-gray-400" style={{ fontSize: "0.68rem" }}>{p.unit}</p>
                        </div>
                      </div>
                    </td>
                    <td className="px-4 py-3 text-gray-600" style={{ fontFamily: "monospace", fontSize: "0.75rem" }}>{p.nip}</td>
                    <td className="px-4 py-3 text-gray-600">{p.jabatan}</td>
                    <td className="px-4 py-3 text-gray-500">{p.unit}</td>
                    <td className="px-4 py-3">
                      <span className="px-2 py-1 rounded-lg bg-gray-100 text-gray-600" style={{ fontFamily: "monospace", fontSize: "0.72rem" }}>
                        {p.password}
                      </span>
                    </td>
                    <td className="px-4 py-3">
                      <span className="px-2 py-1 rounded-full" style={{ background: rb.bg, color: rb.text, fontSize: "0.7rem", fontWeight: 600 }}>
                        {p.role}
                      </span>
                    </td>
                    <td className="px-4 py-3">
                      <span className="flex items-center gap-1 px-2 py-1 rounded-full bg-green-50 text-green-700" style={{ fontSize: "0.68rem", fontWeight: 600, width: "fit-content" }}>
                        <span className="w-1.5 h-1.5 rounded-full bg-green-500" />Aktif
                      </span>
                    </td>
                    <td className="px-4 py-3">
                      <div className="flex items-center gap-1">
                        <button onClick={() => openEdit(p)} className="p-1.5 rounded-lg hover:bg-yellow-50 text-yellow-600" title="Edit">
                          <Edit2 size={14} />
                        </button>
                        <button onClick={() => setDeleteId(p.nip)} className="p-1.5 rounded-lg hover:bg-red-50 text-red-500" title="Hapus">
                          <Trash2 size={14} />
                        </button>
                      </div>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
        <div className="px-4 py-3 border-t flex items-center justify-between" style={{ borderColor: "#f3f4f6" }}>
          <p className="text-gray-400" style={{ fontSize: "0.73rem" }}>
            Menampilkan {filtered.length} dari {pegawai.length} akun pegawai
          </p>
          <p className="text-blue-600" style={{ fontSize: "0.73rem", fontWeight: 600 }}>Total Akun Aktif: {pegawai.length}</p>
        </div>
      </div>

      {/* Add/Edit Modal */}
      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center" style={{ background: "rgba(0,0,0,0.5)" }}>
          <div className="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
            <div className="px-6 py-4 flex items-center justify-between"
              style={{ background: "linear-gradient(135deg, #1e40af, #2563eb)" }}>
              <h3 className="text-white" style={{ fontWeight: 600 }}>
                {editData ? "Edit Akun Pegawai" : "Tambah Akun Pegawai"}
              </h3>
              <button onClick={() => setShowModal(false)} className="text-white/70 hover:text-white">
                <X size={18} />
              </button>
            </div>
            <div className="p-6 space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-gray-600 mb-1" style={{ fontSize: "0.78rem", fontWeight: 600 }}>NIP</label>
                  <input value={form.nip} onChange={(e) => setForm({ ...form, nip: e.target.value })}
                    placeholder="18 digit NIP"
                    className="w-full px-3 py-2 rounded-lg border outline-none text-gray-700"
                    style={{ fontSize: "0.82rem", borderColor: "#e5e7eb", background: "#f9fafb" }} />
                </div>
                <div>
                  <label className="block text-gray-600 mb-1" style={{ fontSize: "0.78rem", fontWeight: 600 }}>Role</label>
                  <select value={form.role} onChange={(e) => setForm({ ...form, role: e.target.value })}
                    className="w-full px-3 py-2 rounded-lg border outline-none text-gray-700"
                    style={{ fontSize: "0.82rem", borderColor: "#e5e7eb", background: "#f9fafb" }}>
                    {["Super Admin", "Admin", "Operator", "Viewer"].map((r) => (
                      <option key={r} value={r}>{r}</option>
                    ))}
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-gray-600 mb-1" style={{ fontSize: "0.78rem", fontWeight: 600 }}>Nama Lengkap</label>
                <input value={form.nama} onChange={(e) => setForm({ ...form, nama: e.target.value })}
                  placeholder="Nama lengkap + gelar"
                  className="w-full px-3 py-2 rounded-lg border outline-none text-gray-700"
                  style={{ fontSize: "0.82rem", borderColor: "#e5e7eb", background: "#f9fafb" }} />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-gray-600 mb-1" style={{ fontSize: "0.78rem", fontWeight: 600 }}>Jabatan</label>
                  <input value={form.jabatan} onChange={(e) => setForm({ ...form, jabatan: e.target.value })}
                    placeholder="Jabatan pegawai"
                    className="w-full px-3 py-2 rounded-lg border outline-none text-gray-700"
                    style={{ fontSize: "0.82rem", borderColor: "#e5e7eb", background: "#f9fafb" }} />
                </div>
                <div>
                  <label className="block text-gray-600 mb-1" style={{ fontSize: "0.78rem", fontWeight: 600 }}>Unit Kerja</label>
                  <select value={form.unit} onChange={(e) => setForm({ ...form, unit: e.target.value })}
                    className="w-full px-3 py-2 rounded-lg border outline-none text-gray-700"
                    style={{ fontSize: "0.82rem", borderColor: "#e5e7eb", background: "#f9fafb" }}>
                    {unitList.map((u) => <option key={u} value={u}>{u}</option>)}
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-gray-600 mb-1" style={{ fontSize: "0.78rem", fontWeight: 600 }}>Password</label>
                <div className="relative">
                  <input type={showPass ? "text" : "password"} value={form.password}
                    onChange={(e) => setForm({ ...form, password: e.target.value })}
                    placeholder="Minimal 6 karakter"
                    className="w-full px-3 py-2 pr-10 rounded-lg border outline-none text-gray-700"
                    style={{ fontSize: "0.82rem", borderColor: "#e5e7eb", background: "#f9fafb" }} />
                  <button type="button" onClick={() => setShowPass(!showPass)}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    {showPass ? <EyeOff size={15} /> : <Eye size={15} />}
                  </button>
                </div>
              </div>

              <div className="flex gap-3 pt-1">
                <button onClick={() => setShowModal(false)}
                  className="flex-1 py-2.5 rounded-xl border text-gray-600"
                  style={{ fontSize: "0.85rem", borderColor: "#e5e7eb" }}>Batal</button>
                <button onClick={handleSave}
                  className="flex-1 py-2.5 rounded-xl text-white"
                  style={{ background: "linear-gradient(135deg, #1e40af, #2563eb)", fontSize: "0.85rem", fontWeight: 600 }}>
                  {editData ? "Simpan Perubahan" : "Tambah Akun"}
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Confirm Delete */}
      {deleteId && (
        <div className="fixed inset-0 z-50 flex items-center justify-center" style={{ background: "rgba(0,0,0,0.5)" }}>
          <div className="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6 text-center">
            <div className="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
              <Trash2 size={24} className="text-red-500" />
            </div>
            <h3 className="text-gray-800 mb-2" style={{ fontWeight: 700 }}>Hapus Akun?</h3>
            <p className="text-gray-500 mb-5" style={{ fontSize: "0.82rem" }}>
              Akun dengan NIP <strong>{deleteId}</strong> akan dihapus secara permanen.
            </p>
            <div className="flex gap-3">
              <button onClick={() => setDeleteId(null)}
                className="flex-1 py-2.5 rounded-xl border text-gray-600"
                style={{ fontSize: "0.85rem", borderColor: "#e5e7eb" }}>Batal</button>
              <button onClick={() => handleDelete(deleteId)}
                className="flex-1 py-2.5 rounded-xl text-white bg-red-500"
                style={{ fontSize: "0.85rem", fontWeight: 600 }}>Hapus</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
