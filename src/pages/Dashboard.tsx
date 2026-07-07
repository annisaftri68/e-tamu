import AdminLayout from "../layouts/AdminLayout";
import { useEffect, useState } from "react";
import API from "../services/api";

import {
  Users,
  UserCheck,
  TrendingUp,
  Clock,
  Eye,
  CheckCircle,
} from "lucide-react";

import {
  ResponsiveContainer,
  AreaChart,
  Area,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
} from "recharts";

interface DashboardData {
  today: number;
  active: number;
  total: number;
  average: number;
}

interface Visitor {
  id: number;
  nama: string;
  instansi: string;
  keperluan: string;
  status: string;
  created_at: string;
}

interface ChartData {
  name: string;
  kunjungan: number;
}

export default function Dashboard() {
  const [dashboard, setDashboard] =
    useState<DashboardData>({
      today: 0,
      active: 0,
      total: 0,
      average: 0,
    });

  const [activeVisitors, setActiveVisitors] =
    useState<Visitor[]>([]);

  const [recentVisitors, setRecentVisitors] =
    useState<Visitor[]>([]);

  const [chartData, setChartData] =
    useState<ChartData[]>([]);

  useEffect(() => {
    getDashboard();
    getVisitors();
    getChartData();

    const interval = setInterval(() => {
      getDashboard();
      getVisitors();
      getChartData();
    }, 5000);

    return () => clearInterval(interval);
  }, []);

  const getDashboard = async () => {
    try {
      const response = await API.get(
        "/dashboard/get.php"
      );

      setDashboard(response.data);
    } catch (error) {
      console.log(
        "Gagal mengambil dashboard:",
        error
      );
    }
  };

  const getVisitors = async () => {
    try {
      const response = await API.get(
        "/visitors/get.php"
      );

      const data = response.data;

      setActiveVisitors(
        data.filter(
          (item: Visitor) =>
            item.status === "Diproses"
        )
      );

      setRecentVisitors(
        data
          .filter(
            (item: Visitor) =>
              item.status === "Selesai"
          )
          .slice(0, 5)
      );
    } catch (error) {
      console.log(
        "Gagal mengambil data tamu:",
        error
      );
    }
  };

  const getChartData = async () => {
    try {
      const response = await API.get(
        "/dashboard/chart.php"
      );

      setChartData(response.data);
    } catch (error) {
      console.log(
        "Gagal mengambil grafik:",
        error
      );
    }
  };

  const stats = [
    {
      title: "Kunjungan Hari Ini",
      value: dashboard.today,
      icon: Users,
      color: "bg-blue-500",
    },
    {
      title: "Sedang Berkunjung",
      value: dashboard.active,
      icon: UserCheck,
      color: "bg-green-500",
    },
    {
      title: "Total Kunjungan",
      value: dashboard.total,
      icon: TrendingUp,
      color: "bg-purple-500",
    },
    {
      title: "Rata-rata per Hari",
      value: dashboard.average,
      icon: Clock,
      color: "bg-orange-500",
    },
  ];

  return (
    <AdminLayout>
      <div className="space-y-8">

        {/* HEADER */}
        <div>
          <h1 className="text-3xl font-bold text-slate-800">
            Dashboard e-Tamu
          </h1>

          <p className="text-gray-500 mt-2">
            Selamat datang di Sistem Manajemen
            Tamu BKPSDM Kabupaten Tegal
          </p>
        </div>

        {/* CARD */}
        <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
          {stats.map((item) => {
            const Icon = item.icon;

            return (
              <div
                key={item.title}
                className="
                  bg-white
                  rounded-2xl
                  p-6
                  shadow-sm
                  border
                  border-gray-100
                  hover:shadow-md
                  transition
                "
              >
                <div className="flex justify-between items-center">
                  <div>
                    <p className="text-sm text-gray-500">
                      {item.title}
                    </p>

                    <h2 className="text-3xl font-bold text-slate-800 mt-2">
                      {item.value}
                    </h2>
                  </div>

                  <div
                    className={`
                      ${item.color}
                      w-16
                      h-16
                      rounded-2xl
                      flex
                      items-center
                      justify-center
                    `}
                  >
                    <Icon
                      size={30}
                      className="text-white"
                    />
                  </div>
                </div>
              </div>
            );
          })}
        </div>

        {/* GRAFIK */}
        <div className="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 className="text-xl font-semibold text-slate-800">
            Grafik Kunjungan 7 Hari Terakhir
          </h2>

          <p className="text-gray-500 mb-6">
            Statistik kunjungan tamu BKPSDM
          </p>

          <ResponsiveContainer
            width="100%"
            height={320}
          >
            <AreaChart data={chartData}>
              <CartesianGrid strokeDasharray="3 3" />

              <XAxis dataKey="name" />

              <YAxis />

              <Tooltip />

              <Area
                type="monotone"
                dataKey="kunjungan"
                stroke="#2563eb"
                fill="#93c5fd"
              />
            </AreaChart>
          </ResponsiveContainer>
        </div>

        {/* BAWAH */}
        <div className="grid lg:grid-cols-2 gap-6">

          {/* TAMU AKTIF */}
          <div className="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div className="flex justify-between mb-6">
              <div>
                <h2 className="text-xl font-semibold">
                  Tamu Sedang Berkunjung
                </h2>

                <p className="text-gray-500 text-sm">
                  Daftar tamu yang sedang berada di kantor
                </p>
              </div>

              <span className="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm h-fit">
                {activeVisitors.length} Aktif
              </span>
            </div>

            <div className="space-y-4">
              {activeVisitors.length > 0 ? (
                activeVisitors.map((v) => (
                  <div
                    key={v.id}
                    className="
                      border
                      rounded-xl
                      p-4
                      flex
                      justify-between
                      items-start
                      hover:bg-gray-50
                      transition
                    "
                  >
                    <div>
                      <h3 className="font-semibold text-slate-800">
                        {v.nama}
                      </h3>

                      <p className="text-sm text-gray-500 mt-1">
                        {v.instansi}
                      </p>

                      <p className="text-xs text-gray-400 mt-2">
                        {v.keperluan}
                      </p>
                    </div>

                    <div className="text-right">
                      <p className="text-sm text-gray-500">
                        {new Date(
                          v.created_at
                        ).toLocaleTimeString(
                          "id-ID",
                          {
                            hour: "2-digit",
                            minute: "2-digit",
                          }
                        )}
                      </p>

                      <button className="mt-2 text-blue-600">
                        <Eye size={18} />
                      </button>
                    </div>
                  </div>
                ))
              ) : (
                <p className="text-center text-gray-500 py-6">
                  Tidak ada tamu aktif
                </p>
              )}
            </div>
          </div>

          {/* KUNJUNGAN TERAKHIR */}
          <div className="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 className="text-xl font-semibold">
              Kunjungan Terakhir
            </h2>

            <p className="text-gray-500 mb-6 text-sm">
              Tamu yang telah selesai berkunjung
            </p>

            <div className="space-y-4">
              {recentVisitors.length > 0 ? (
                recentVisitors.map((v) => (
                  <div
                    key={v.id}
                    className="
                      border
                      rounded-xl
                      p-4
                      hover:bg-gray-50
                      transition
                    "
                  >
                    <div className="flex justify-between">
                      <h3 className="font-semibold text-slate-800">
                        {v.nama}
                      </h3>

                      <span className="flex items-center gap-1 text-green-600 text-sm">
                        <CheckCircle size={16} />
                        Selesai
                      </span>
                    </div>

                    <p className="text-sm text-gray-500 mt-2">
                      {v.instansi}
                    </p>

                    <div className="flex justify-between mt-3">
                      <p className="text-xs text-gray-400">
                        {v.keperluan}
                      </p>

                      <p className="text-xs text-gray-400">
                        {new Date(
                          v.created_at
                        ).toLocaleString("id-ID")}
                      </p>
                    </div>
                  </div>
                ))
              ) : (
                <p className="text-center text-gray-500 py-6">
                  Belum ada kunjungan selesai
                </p>
              )}
            </div>
          </div>

        </div>
      </div>
    </AdminLayout>
  );
}