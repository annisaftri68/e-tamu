import { ReactNode, useState, useContext } from "react";
import { useNavigate } from "react-router-dom";
import { AuthContext } from "../context/AuthContext";
import Sidebar from "../components/Sidebar";

interface AdminLayoutProps {
  children: ReactNode;
}

export default function AdminLayout({
  children,
}: AdminLayoutProps) {
  const navigate = useNavigate();
  const { user, logout }: any = useContext(AuthContext);

  const [activeMenu, setActiveMenu] =
    useState("dashboard");

  const handleLogout = () => {
    logout();
    navigate("/");
  };

  return (
    <div className="flex min-h-screen bg-slate-100">
      <Sidebar
        activeMenu={activeMenu}
        onMenuChange={setActiveMenu}
        onLogout={handleLogout}
        user={{
          nama: user?.name || "Administrator",
          jabatan: user?.username || "Admin BKPSDM",
          nip: user?.nip || "",
        }}
      />

      <main className="flex-1 p-6">
        {children}
      </main>
    </div>
  );
}
