import { ReactNode, useState } from "react";
import { useNavigate } from "react-router-dom";
import Sidebar from "../components/Sidebar";

interface AdminLayoutProps {
  children: ReactNode;
}

export default function AdminLayout({
  children,
}: AdminLayoutProps) {
  const navigate = useNavigate();

  const [activeMenu, setActiveMenu] =
    useState("dashboard");

  const user = {
    nama: "Administrator",
    jabatan: "Admin BKPSDM",
    nip: "198701012024001",
  };

  const handleLogout = () => {
    navigate("/");
  };

  return (
    <div className="flex min-h-screen bg-slate-100">
      <Sidebar
        activeMenu={activeMenu}
        onMenuChange={setActiveMenu}
        onLogout={handleLogout}
        user={user}
      />

      <main className="flex-1 p-6">
        {children}
      </main>
    </div>
  );
}