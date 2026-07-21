import {
  BrowserRouter,
  Routes,
  Route,
  Navigate,
} from "react-router-dom";
import { AuthProvider } from "./context/AuthContext";

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
import PrivateRoute from "./routes/PrivateRoute";

function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Routes>
          <Route path="/" element={<Login />} />

          <Route
            path="/dashboard"
            element={
              <PrivateRoute>
                <Dashboard />
              </PrivateRoute>
            }
          />

          <Route
            path="/data-tamu"
            element={
              <PrivateRoute>
                <DataTamu />
              </PrivateRoute>
            }
          />

          <Route
            path="/kunjungan"
            element={
              <PrivateRoute>
                <Kunjungan />
              </PrivateRoute>
            }
          />

          <Route
            path="/jadwal"
            element={
              <PrivateRoute>
                <Jadwal />
              </PrivateRoute>
            }
          />

          <Route
            path="/laporan"
            element={
              <PrivateRoute>
                <Laporan />
              </PrivateRoute>
            }
          />

          <Route
            path="/statistik"
            element={
              <PrivateRoute>
                <Statistik />
              </PrivateRoute>
            }
          />

          <Route
            path="/akun"
            element={
              <PrivateRoute>
                <Akun />
              </PrivateRoute>
            }
          />

          <Route
            path="/pengaturan"
            element={
              <PrivateRoute>
                <Pengaturan />
              </PrivateRoute>
            }
          />

          <Route path="/kiosk" element={<Kiosk />} />

          <Route
            path="*"
            element={<Navigate to="/dashboard" replace />}
          />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  );
}

export default App;
