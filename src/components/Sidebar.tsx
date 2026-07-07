import { useState } from "react";
import {
  LayoutDashboard,
  Users,
  BarChart2,
  Settings,
  LogOut,
  ChevronLeft,
  ChevronRight,
  FileText,
  UserCheck,
  Calendar,
  Menu,
  Shield,
} from "lucide-react";

import {
  useNavigate,
  useLocation,
} from "react-router-dom";

interface SidebarProps {
  activeMenu: string;
  onMenuChange: (menu: string) => void;
  onLogout: () => void;
  user: {
    nama: string;
    jabatan: string;
    nip: string;
  };
}

const menuItems = [
  {
    id: "dashboard",
    label: "Dashboard",
    icon: LayoutDashboard,
    path: "/dashboard",
  },
  {
    id: "tamu",
    label: "Data Tamu",
    icon: Users,
    path: "/data-tamu",
  },
  {
    id: "kunjungan",
    label: "Kunjungan Hari Ini",
    icon: UserCheck,
    path: "/kunjungan",
  },
  {
    id: "jadwal",
    label: "Jadwal Tamu",
    icon: Calendar,
    path: "/jadwal",
  },
  {
    id: "laporan",
    label: "Laporan",
    icon: FileText,
    path: "/laporan",
  },
  {
    id: "statistik",
    label: "Statistik",
    icon: BarChart2,
    path: "/statistik",
  },
  {
    id: "pengaturan",
    label: "Pengaturan",
    icon: Settings,
    path: "/pengaturan",
  },
];

export default function Sidebar({
  activeMenu,
  onMenuChange,
  onLogout,
  user,
}: SidebarProps) {
  const [collapsed, setCollapsed] =
    useState(false);

  const navigate = useNavigate();
  const location = useLocation();

  return (
    <aside
      className="h-screen flex flex-col relative transition-all duration-300"
      style={{
        width: collapsed ? "75px" : "250px",
        background:
          "linear-gradient(180deg,#0f2d6b 0%,#1e3a8a 45%,#2563eb 100%)",
        boxShadow:
          "4px 0 20px rgba(0,0,0,0.2)",
      }}
    >
      {/* TOGGLE */}
      <button
        onClick={() =>
          setCollapsed(!collapsed)
        }
        className="absolute -right-3 top-20 w-7 h-7 rounded-full flex items-center justify-center shadow-lg"
        style={{
          background: "#2563eb",
          color: "#fff",
          border: "2px solid white",
        }}
      >
        {collapsed ? (
          <ChevronRight size={14} />
        ) : (
          <ChevronLeft size={14} />
        )}
      </button>

      {/* LOGO */}
      <div
        className="flex items-center gap-3 p-4 border-b"
        style={{
          borderColor:
            "rgba(255,255,255,0.1)",
        }}
      >
        <div
          className="w-11 h-11 rounded-xl flex items-center justify-center"
          style={{
            background:
              "rgba(255,255,255,0.15)",
          }}
        >
          <Menu
            size={22}
            className="text-white"
          />
        </div>

        {!collapsed && (
          <div>
            <h1 className="text-white font-bold text-sm">
              E-TAMU DIGITAL
            </h1>

            <p className="text-blue-200 text-xs">
              BKPSDM Kab. Tegal
            </p>
          </div>
        )}
      </div>

      {/* USER */}
      {!collapsed && (
        <div
          className="m-3 p-3 rounded-xl"
          style={{
            background:
              "rgba(255,255,255,0.08)",
            border:
              "1px solid rgba(255,255,255,0.1)",
          }}
        >
          <div className="flex items-center gap-3">
            <div
              className="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold"
              style={{
                background:
                  "linear-gradient(135deg,#60a5fa,#2563eb)",
              }}
            >
              {user.nama
                ? user.nama.charAt(0)
                : "A"}
            </div>

            <div className="overflow-hidden">
              <p className="text-white text-sm font-semibold truncate">
                {user.nama}
              </p>

              <p className="text-blue-200 text-xs truncate">
                {user.jabatan}
              </p>

              <p className="text-blue-300 text-[11px] truncate">
                {user.nip}
              </p>
            </div>
          </div>
        </div>
      )}

      {/* MENU */}
      <nav className="flex-1 px-2 py-2 overflow-y-auto">
        {menuItems.map((item) => {
          const Icon = item.icon;

          const isActive =
            location.pathname === item.path;

          return (
            <button
              key={item.id}
              onClick={() => {
                onMenuChange(item.id);
                navigate(item.path);
              }}
              className="w-full flex items-center gap-3 px-3 py-3 rounded-xl mb-1 transition-all"
              style={{
                background: isActive
                  ? "rgba(255,255,255,0.18)"
                  : "transparent",

                color: isActive
                  ? "#ffffff"
                  : "rgba(255,255,255,0.7)",

                borderLeft: isActive
                  ? "4px solid #60a5fa"
                  : "4px solid transparent",
              }}
            >
              <Icon
                size={18}
                className="flex-shrink-0"
              />

              {!collapsed && (
                <span className="text-sm">
                  {item.label}
                </span>
              )}
            </button>
          );
        })}
      </nav>

      {/* LOGOUT */}
      <div
        className="p-3 border-t"
        style={{
          borderColor:
            "rgba(255,255,255,0.1)",
        }}
      >
        <button
          onClick={onLogout}
          className="w-full flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-red-500/10 transition-all"
          style={{
            color: "#fca5a5",
          }}
        >
          <LogOut size={18} />

          {!collapsed && (
            <span className="text-sm">
              Keluar
            </span>
          )}
        </button>
      </div>
    </aside>
  );
}