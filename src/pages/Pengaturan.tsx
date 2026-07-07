import { useState } from "react";
import {
  Save,
  Lock,
  CheckCircle,
  AlertCircle,
} from "lucide-react";

interface UserType {
  nama: string;
  jabatan: string;
  nip: string;
}

interface PengaturanProps {
  user?: UserType;
  onUpdateUser?: (updated: UserType) => void;
}

export default function Pengaturan({
  user,
  onUpdateUser,
}: PengaturanProps) {
  const currentUser = user || {
    nama: "Administrator",
    jabatan: "Admin BKPSDM",
    nip: "000000",
  };

  const [profil, setProfil] = useState({
    nama: currentUser.nama,
    jabatan: currentUser.jabatan,
    unit: "",
    email: "",
  });

  const [passwords, setPasswords] = useState({
    lama: "",
    baru: "",
    konfirmasi: "",
  });

  const [profilMsg, setProfilMsg] = useState<any>(null);
  const [passMsg, setPassMsg] = useState<any>(null);

  const [toggles, setToggles] = useState([
    true,
    true,
    false,
    false,
  ]);

  const handleSaveProfil = () => {
    if (onUpdateUser) {
      onUpdateUser({
        nip: currentUser.nip,
        nama: profil.nama,
        jabatan: profil.jabatan,
      });
    }

    setProfilMsg({
      type: "ok",
      text: "Profil berhasil diperbarui",
    });

    setTimeout(() => {
      setProfilMsg(null);
    }, 3000);
  };

  const handleSavePassword = () => {
    if (
      !passwords.lama ||
      !passwords.baru ||
      !passwords.konfirmasi
    ) {
      setPassMsg({
        type: "err",
        text: "Semua field wajib diisi",
      });
      return;
    }

    if (
      passwords.baru !==
      passwords.konfirmasi
    ) {
      setPassMsg({
        type: "err",
        text: "Konfirmasi password tidak cocok",
      });
      return;
    }

    setPassMsg({
      type: "ok",
      text: "Password berhasil diubah",
    });

    setPasswords({
      lama: "",
      baru: "",
      konfirmasi: "",
    });

    setTimeout(() => {
      setPassMsg(null);
    }, 3000);
  };

  const sysConfig = [
    {
      label: "Notifikasi Email",
      desc: "Terima notifikasi via email",
    },
    {
      label: "Auto Refresh Data",
      desc: "Refresh otomatis",
    },
    {
      label: "Mode Gelap",
      desc: "Aktifkan dark mode",
    },
    {
      label: "Suara Notifikasi",
      desc: "Aktifkan suara",
    },
  ];

  return (
    <div className="p-6 space-y-6">
      <div>
        <h1 className="text-3xl font-bold">
          Pengaturan
        </h1>

        <p className="text-gray-500">
          Kelola profil dan konfigurasi sistem
        </p>
      </div>

      {/* PROFIL */}
      <div className="bg-white p-6 rounded-xl shadow border">
        <h2 className="text-lg font-semibold mb-4">
          Edit Profil
        </h2>

        {profilMsg && (
          <div
            className={`mb-4 p-3 rounded flex items-center gap-2 ${
              profilMsg.type === "ok"
                ? "bg-green-50 text-green-600"
                : "bg-red-50 text-red-600"
            }`}
          >
            {profilMsg.type === "ok" ? (
              <CheckCircle size={18} />
            ) : (
              <AlertCircle size={18} />
            )}
            {profilMsg.text}
          </div>
        )}

        <div className="grid md:grid-cols-2 gap-4">
          <input
            value={currentUser.nip}
            disabled
            className="border p-3 rounded"
          />

          <input
            value={profil.nama}
            placeholder="Nama"
            onChange={(e) =>
              setProfil({
                ...profil,
                nama: e.target.value,
              })
            }
            className="border p-3 rounded"
          />

          <input
            value={profil.jabatan}
            placeholder="Jabatan"
            onChange={(e) =>
              setProfil({
                ...profil,
                jabatan: e.target.value,
              })
            }
            className="border p-3 rounded"
          />

          <input
            value={profil.unit}
            placeholder="Unit Kerja"
            onChange={(e) =>
              setProfil({
                ...profil,
                unit: e.target.value,
              })
            }
            className="border p-3 rounded"
          />

          <input
            value={profil.email}
            placeholder="Email"
            onChange={(e) =>
              setProfil({
                ...profil,
                email: e.target.value,
              })
            }
            className="border p-3 rounded"
          />
        </div>

        <button
          onClick={handleSaveProfil}
          className="mt-4 bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2"
        >
          <Save size={18} />
          Simpan Profil
        </button>
      </div>

      {/* PASSWORD */}
      <div className="bg-white p-6 rounded-xl shadow border">
        <h2 className="text-lg font-semibold mb-4">
          Ubah Password
        </h2>

        {passMsg && (
          <div
            className={`mb-4 p-3 rounded flex items-center gap-2 ${
              passMsg.type === "ok"
                ? "bg-green-50 text-green-600"
                : "bg-red-50 text-red-600"
            }`}
          >
            {passMsg.type === "ok" ? (
              <CheckCircle size={18} />
            ) : (
              <AlertCircle size={18} />
            )}
            {passMsg.text}
          </div>
        )}

        <div className="space-y-3">
          <input
            type="password"
            placeholder="Password Lama"
            value={passwords.lama}
            onChange={(e) =>
              setPasswords({
                ...passwords,
                lama: e.target.value,
              })
            }
            className="border p-3 rounded w-full"
          />

          <input
            type="password"
            placeholder="Password Baru"
            value={passwords.baru}
            onChange={(e) =>
              setPasswords({
                ...passwords,
                baru: e.target.value,
              })
            }
            className="border p-3 rounded w-full"
          />

          <input
            type="password"
            placeholder="Konfirmasi Password"
            value={passwords.konfirmasi}
            onChange={(e) =>
              setPasswords({
                ...passwords,
                konfirmasi: e.target.value,
              })
            }
            className="border p-3 rounded w-full"
          />
        </div>

        <button
          onClick={handleSavePassword}
          className="mt-4 bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2"
        >
          <Lock size={18} />
          Update Password
        </button>
      </div>

      {/* KONFIGURASI */}
      <div className="bg-white p-6 rounded-xl shadow border">
        <h2 className="text-lg font-semibold mb-4">
          Konfigurasi Sistem
        </h2>

        {sysConfig.map((item, index) => (
          <div
            key={item.label}
            className="flex justify-between border-b py-3"
          >
            <div>
              <h3>{item.label}</h3>
              <p className="text-sm text-gray-500">
                {item.desc}
              </p>
            </div>

            <input
              type="checkbox"
              checked={toggles[index]}
              onChange={() =>
                setToggles((prev) =>
                  prev.map((v, i) =>
                    i === index ? !v : v
                  )
                )
              }
            />
          </div>
        ))}
      </div>
    </div>
  );
}