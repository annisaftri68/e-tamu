import { useEffect, useState } from "react";
import API from "../services/api";
import {
  CheckCircle,
  Clock,
  XCircle,
  RefreshCw,
} from "lucide-react";

interface Visitor {
  id: number;
  nama: string;
  instansi: string;
  keperluan: string;
  nomor_antrian: string;
  status: string;
  created_at: string;
}

const statusStyle: Record<
  string,
  {
    bg: string;
    text: string;
    icon: React.ReactNode;
  }
> = {
  Selesai: {
    bg: "#dcfce7",
    text: "#16a34a",
    icon: <CheckCircle size={14} />,
  },

  Diproses: {
    bg: "#dbeafe",
    text: "#1d4ed8",
    icon: <Clock size={14} />,
  },

  Menunggu: {
    bg: "#fef9c3",
    text: "#b45309",
    icon: <Clock size={14} />,
  },

  Batal: {
    bg: "#fee2e2",
    text: "#dc2626",
    icon: <XCircle size={14} />,
  },
};

export default function KunjunganHariIni() {
  const [antrian, setAntrian] =
    useState<Visitor[]>([]);

  const now =
    new Date().toLocaleTimeString(
      "id-ID",
      {
        hour: "2-digit",
        minute: "2-digit",
      }
    );

  useEffect(() => {
    getVisitors();

    const interval =
      setInterval(() => {
        getVisitors();
      }, 5000);

    return () =>
      clearInterval(interval);
  }, []);

  const getVisitors = async () => {
    try {
      const response =
        await API.get(
          "/visitors/get.php"
        );

      setAntrian(response.data);
    } catch (error) {
      console.log(
        "Gagal mengambil data",
        error
      );
    }
  };

  const updateStatus =
    async (
      id: number,
      status: string
    ) => {
      try {
        await API.post(
          "/visitors/update-status.php",
          {
            id,
            status,
          }
        );

        getVisitors();
      } catch (error) {
        console.log(
          "Gagal update status",
          error
        );
      }
    };

  const counts = {
    total: antrian.length,

    selesai: antrian.filter(
      (a) => a.status === "Selesai"
    ).length,

    proses: antrian.filter(
      (a) => a.status === "Diproses"
    ).length,

    menunggu: antrian.filter(
      (a) => a.status === "Menunggu"
    ).length,
  };

  return (
    <div className="p-6 h-full overflow-y-auto">
      {/* Header */}
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1
            className="text-gray-800"
            style={{
              fontWeight: 700,
            }}
          >
            Kunjungan Hari Ini
          </h1>

          <p
            className="text-gray-500"
            style={{
              fontSize: "0.82rem",
            }}
          >
            Pukul {now} WIB
          </p>
        </div>

        <button
          onClick={getVisitors}
          className="
          flex
          items-center
          gap-2
          px-3
          py-2
          rounded-xl
          border
          text-gray-600
          hover:bg-gray-50
        "
        >
          <RefreshCw size={14} />
          Refresh
        </button>
      </div>

      {/* Summary */}
      <div className="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        {[
          {
            label: "Total Antrian",
            value: counts.total,
            color: "#2563eb",
          },

          {
            label: "Selesai",
            value: counts.selesai,
            color: "#16a34a",
          },

          {
            label: "Sedang Diproses",
            value: counts.proses,
            color: "#1d4ed8",
          },

          {
            label: "Menunggu",
            value: counts.menunggu,
            color: "#d97706",
          },
        ].map((s) => (
          <div
            key={s.label}
            className="
            bg-white
            rounded-2xl
            p-4
            shadow-sm
            border
            text-center
          "
          >
            <p
              className="text-3xl"
              style={{
                color: s.color,
                fontWeight: 700,
              }}
            >
              {s.value}
            </p>

            <p
              className="text-gray-500"
              style={{
                fontSize: "0.75rem",
              }}
            >
              {s.label}
            </p>
          </div>
        ))}
      </div>

      {/* Data Antrian */}
      <div className="space-y-3">
        {antrian.map((a) => {
          const st =
            statusStyle[a.status] ||
            statusStyle.Menunggu;

          return (
            <div
              key={a.id}
              className="
                bg-white
                rounded-2xl
                p-4
                shadow-sm
                border
                flex
                items-center
                gap-4
              "
              style={{
                borderLeft:
                  `4px solid ${st.text}`,
              }}
            >
              {/* Nomor */}
              <div
                className="
                w-12
                h-12
                rounded-xl
                flex
                items-center
                justify-center
              "
                style={{
                  background: st.bg,
                }}
              >
                <span
                  style={{
                    color: st.text,
                    fontWeight: 700,
                  }}
                >
                  {a.nomor_antrian}
                </span>
              </div>

              {/* Info */}
              <div className="flex-1">
                <p className="font-semibold">
                  {a.nama}
                </p>

                <p className="text-sm text-gray-500">
                  {a.instansi}
                </p>

                <p className="text-xs text-gray-400">
                  {a.keperluan}
                </p>
              </div>

              {/* Waktu */}
              <div className="text-center">
                <p className="text-sm">
                  {new Date(
                    a.created_at
                  ).toLocaleTimeString(
                    "id-ID",
                    {
                      hour:
                        "2-digit",
                      minute:
                        "2-digit",
                    }
                  )}
                </p>
              </div>

              {/* Status */}
              <span
                className="
                flex
                items-center
                gap-1
                px-3
                py-1
                rounded-full
              "
                style={{
                  background: st.bg,
                  color: st.text,
                }}
              >
                {st.icon}
                {a.status}
              </span>

              {/* Action */}
              <div className="flex gap-2">
                {a.status ===
                  "Menunggu" && (
                  <button
                    onClick={() =>
                      updateStatus(
                        a.id,
                        "Diproses"
                      )
                    }
                    className="
                    px-3
                    py-1
                    rounded-lg
                    bg-blue-600
                    text-white
                  "
                  >
                    Proses
                  </button>
                )}

                {a.status ===
                  "Diproses" && (
                  <button
                    onClick={() =>
                      updateStatus(
                        a.id,
                        "Selesai"
                      )
                    }
                    className="
                    px-3
                    py-1
                    rounded-lg
                    bg-green-600
                    text-white
                  "
                  >
                    Selesai
                  </button>
                )}

                {a.status !==
                  "Selesai" && (
                  <button
                    onClick={() =>
                      updateStatus(
                        a.id,
                        "Batal"
                      )
                    }
                    className="
                    px-3
                    py-1
                    rounded-lg
                    bg-red-100
                    text-red-600
                  "
                  >
                    Batal
                  </button>
                )}
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}