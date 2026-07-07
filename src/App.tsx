import {
  BrowserRouter,
  Routes,
  Route,
  Navigate,
} from "react-router-dom";

import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";
import DataTamu from "./pages/DataTamu";
import Statistik from "./pages/Statistik";
import Laporan from "./pages/Laporan";
import Kiosk from "./pages/Kiosk";
import Kunjungan from "./components/KunjunganHariIni";
import Jadwal from "./pages/Jadwal";
import Akun from "./pages/Akun";
import Pengaturan from "./pages/Pengaturan";
import AdminLayout from "./layouts/AdminLayout";

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route
          path="/"
          element={<Login />}
        />

        <Route
          path="/dashboard"
          element={<Dashboard />}
        />

        <Route
          path="/data-tamu"
          element={<DataTamu />}
        />

        <Route
          path="/kunjungan"
          element={<Kunjungan />}
        />

        <Route
          path="/jadwal"
          element={<Jadwal />}
        />

        <Route
          path="/laporan"
          element={<Laporan />}
        />

        <Route
          path="/statistik"
          element={<Statistik />}
        />

        <Route
          path="/akun"
          element={<Akun />}
        />

        <Route
          path="/pengaturan"
          element={<Pengaturan />}
        />

        <Route
          path="/kiosk"
          element={<Kiosk />}
        />

        <Route
          path="*"
          element={
            <Navigate
              to="/dashboard"
              replace
            />
          }
        />
      </Routes>
    </BrowserRouter>
  );
}

export default App;