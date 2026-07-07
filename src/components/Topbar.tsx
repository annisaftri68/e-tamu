import { Bell, Search, ChevronDown, LogOut } from "lucide-react";
import { useState } from "react";

const menuLabels: Record<string, string> = {
  dashboard: "Dashboard",
  tamu: "Data Tamu",
  kunjungan: "Kunjungan Hari Ini",
  jadwal: "Jadwal Tamu",
  laporan: "Laporan",
  statistik: "Statistik",
  notifikasi: "Notifikasi",
  pengaturan: "Pengaturan",
};

interface TopbarProps {
  activeMenu: string;
  user: { nama: string; jabatan: string; nip: string };
  onLogout: () => void;
}

export function Topbar({ activeMenu, user, onLogout }: TopbarProps) {
  const [showDropdown, setShowDropdown] = useState(false);

  return (
    <header className="flex items-center justify-between px-6 py-3 bg-white border-b shadow-sm" style={{ borderColor: "#e5e7eb", flexShrink: 0 }}>
      {/* Title */}
      <div>
        <p className="text-gray-400" style={{ fontSize: "0.7rem" }}>BKPSDM Kabupaten Tegal · E-Tamu Digital</p>
        <h2 className="text-gray-800" style={{ fontSize: "1rem", fontWeight: 700 }}>{menuLabels[activeMenu] || "Dashboard"}</h2>
      </div>

      {/* Search */}
      <div className="hidden md:flex items-center gap-2 px-3 py-2 rounded-xl border flex-1 max-w-xs mx-6"
        style={{ borderColor: "#e5e7eb", background: "#f9fafb" }}>
        <Search size={14} className="text-gray-400" />
        <input placeholder="Cari tamu, pegawai..." className="bg-transparent outline-none text-gray-600 flex-1"
          style={{ fontSize: "0.8rem" }} />
      </div>

      {/* Right */}
      <div className="flex items-center gap-3">
        {/* Notif */}
        <button className="relative w-9 h-9 rounded-xl flex items-center justify-center hover:bg-blue-50"
          style={{ border: "1px solid #e5e7eb" }}>
          <Bell size={17} className="text-gray-600" />
          <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full" />
        </button>

        {/* User */}
        <div className="relative">
          <button onClick={() => setShowDropdown(!showDropdown)}
            className="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-blue-50 transition-colors"
            style={{ border: "1px solid #e5e7eb" }}>
            <div className="w-7 h-7 rounded-lg flex items-center justify-center text-white"
              style={{ background: "linear-gradient(135deg, #1e40af, #2563eb)", fontSize: "0.75rem", fontWeight: 700 }}>
              {user.nama.charAt(0)}
            </div>
            <div className="hidden sm:block text-left">
              <p className="text-gray-800" style={{ fontSize: "0.75rem", fontWeight: 600, lineHeight: 1.2 }}>
                {user.nama.split(",")[0].split(" ").slice(0, 2).join(" ")}
              </p>
              <p className="text-gray-400" style={{ fontSize: "0.65rem" }}>{user.jabatan}</p>
            </div>
            <ChevronDown size={14} className="text-gray-400" />
          </button>

          {showDropdown && (
            <div className="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border z-50 overflow-hidden"
              style={{ borderColor: "#e5e7eb" }}>
              <div className="px-4 py-3 border-b" style={{ borderColor: "#f3f4f6" }}>
                <p className="text-gray-800" style={{ fontSize: "0.8rem", fontWeight: 600 }}>{user.nama.split(",")[0]}</p>
                <p className="text-gray-400" style={{ fontSize: "0.7rem" }}>NIP: {user.nip}</p>
              </div>
              <button onClick={() => { setShowDropdown(false); onLogout(); }}
                className="w-full flex items-center gap-2 px-4 py-2.5 hover:bg-red-50 text-red-600 transition-colors"
                style={{ fontSize: "0.8rem" }}>
                <LogOut size={14} /> Keluar dari Sistem
              </button>
            </div>
          )}
        </div>
      </div>
    </header>
  );
}
