import { useEffect, useState } from "react";
import API from "../services/api";
import AdminLayout from "../layouts/AdminLayout";

import {
  Users,
  Clock,
  CheckCircle,
} from "lucide-react";

import {
  Bar,
  Doughnut,
  Line,
} from "react-chartjs-2";

import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  ArcElement,
  Tooltip,
  Legend,
} from "chart.js";

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  ArcElement,
  Tooltip,
  Legend
);

export default function Statistik() {
  const [loading, setLoading] = useState(true);
  const [summary, setSummary] = useState({
    total: 0,
    menunggu: 0,
    selesai: 0,
  });

  const [statusData, setStatusData] = useState<any[]>([]);
  const [bulananData, setBulananData] = useState<any[]>([]);
  const [bidangData, setBidangData] = useState<any[]>([]);

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      const [
        summaryRes,
        statusRes,
        bulananRes,
        bidangRes,
      ] = await Promise.all([
        API.get("/statistik/summary.php"),
        API.get("/statistik/status.php"),
        API.get("/statistik/bulanan.php"),
        API.get("/statistik/bidang.php"),
      ]);

      console.log("Summary :", summaryRes.data);
      console.log("Status :", statusRes.data);
      console.log("Bulanan :", bulananRes.data);
      console.log("Bidang :", bidangRes.data);

      // Gunakan fallback array kosong jika data dari API belum tersedia / kosong
      setSummary(summaryRes.data || { total: 0, menunggu: 0, selesai: 0 });
      setStatusData(statusRes.data || []);
      setBulananData(bulananRes.data || []);
      setBidangData(bidangRes.data || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <AdminLayout>
        <div className="p-10 text-center text-slate-500 font-medium animate-pulse">
          Memuat statistik...
        </div>
      </AdminLayout>
    );
  }

  return (
    <AdminLayout>
      <div className="p-6 bg-slate-50 min-h-screen">
        <h1 className="text-3xl font-bold text-slate-800">
          Statistik
        </h1>

        <p className="text-gray-500 mt-2">
          Statistik kunjungan tamu BKPSDM Kabupaten Tegal
        </p>

        {/* CARD STATISTIK */}
        <div className="grid md:grid-cols-3 gap-5 mt-8">
          <div className="bg-white rounded-2xl shadow-sm p-6">
            <div className="flex justify-between items-center">
              <div>
                <p className="text-gray-500 text-sm">
                  Total Kunjungan
                </p>
                <h2 className="text-3xl font-bold mt-2">
                  {summary.total}
                </h2>
              </div>

              <div className="bg-blue-500 p-4 rounded-xl">
                <Users
                  size={28}
                  className="text-white"
                />
              </div>
            </div>
          </div>

          <div className="bg-white rounded-2xl shadow-sm p-6">
            <div className="flex justify-between items-center">
              <div>
                <p className="text-gray-500 text-sm">
                  Menunggu
                </p>
                <h2 className="text-3xl font-bold mt-2">
                  {summary.menunggu}
                </h2>
              </div>

              <div className="bg-yellow-500 p-4 rounded-xl">
                <Clock
                  size={28}
                  className="text-white"
                />
              </div>
            </div>
          </div>

          <div className="bg-white rounded-2xl shadow-sm p-6">
            <div className="flex justify-between items-center">
              <div>
                <p className="text-gray-500 text-sm">
                  Selesai
                </p>
                <h2 className="text-3xl font-bold mt-2">
                  {summary.selesai}
                </h2>
              </div>

              <div className="bg-green-500 p-4 rounded-xl">
                <CheckCircle
                  size={28}
                  className="text-white"
                />
              </div>
            </div>
          </div>
        </div>

        {/* GRAFIK BULANAN DAN STATUS */}
        <div className="grid md:grid-cols-2 gap-6 mt-8">
          <div className="bg-white rounded-2xl shadow p-6">
            <h2 className="font-bold mb-4">
              Kunjungan per Bulan
            </h2>
            <div className="h-80">
              <Line
                data={{
                  labels: bulananData.map((i) => i.bulan),
                  datasets: [
                    {
                      label: "Jumlah Tamu",
                      data: bulananData.map((i) => Number(i.total)),
                      borderColor: "#2563eb",
                      backgroundColor: "#60a5fa",
                      tension: 0.3,
                      fill: false,
                    },
                  ],
                }}
                options={{
                  responsive: true,
                  maintainAspectRatio: false,
                  plugins: {
                    legend: {
                      display: true,
                    },
                  },
                }}
              />
            </div>
          </div>

          <div className="bg-white rounded-2xl shadow p-6">
            <h2 className="font-bold mb-4">
              Status Kunjungan
            </h2>
            <div className="h-80">
              <Doughnut
                data={{
                  labels: statusData.map((i) => i.status),
                  datasets: [
                    {
                      data: statusData.map((i) => Number(i.total)),
                      backgroundColor: [
                        "#FACC15",
                        "#3B82F6",
                        "#22C55E",
                      ],
                    },
                  ],
                }}
                options={{
                  responsive: true,
                  maintainAspectRatio: false,
                }}
              />
            </div>
          </div>
        </div>

        {/* GRAFIK TUJUAN BIDANG */}
        <div className="bg-white rounded-2xl shadow p-6 mt-8">
          <h2 className="font-bold mb-4">
            Tujuan Bidang
          </h2>
          <div className="h-80">
            <Bar
              data={{
                labels: bidangData.map((i) => i.bidang),
                datasets: [
                  {
                    label: "Jumlah",
                    data: bidangData.map((i) => Number(i.total)),
                    backgroundColor: "#2563eb",
                  },
                ],
              }}
              options={{
                responsive: true,
                maintainAspectRatio: false,
              }}
            />
          </div>
        </div>

      </div>
    </AdminLayout>
  );
}