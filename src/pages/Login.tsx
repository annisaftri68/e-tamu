import { useState } from "react";
import { useNavigate } from "react-router-dom";
import bgInstansi from "../assets/bg-instansi.jpeg";
import logoInstansi from "../assets/logo-dinas-bkbsdm.png";

export default function Login() {
  const navigate = useNavigate();

  const [username, setUsername] =
    useState("");

  const [password, setPassword] =
    useState("");

  const handleLogin = () => {
    if (
      username === "admin" &&
      password === "admin"
    ) {
      localStorage.setItem(
        "nip",
        "198701012024001"
      );

      localStorage.setItem(
        "nama",
        "Administrator"
      );

      localStorage.setItem(
        "jabatan",
        "Admin BKPSDM"
      );

      navigate("/dashboard");
    } else {
      alert(
        "Username atau Password salah!"
      );
    }
  };

  return (
    <div
      style={{
        height: "100vh",
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        backgroundImage: `url(${bgInstansi})`,
        backgroundSize: "cover",
        backgroundPosition: "center",
      }}
    >
      <div
        style={{
          width: "380px",
          background: "white",
          padding: "35px",
          borderRadius: "20px",
          boxShadow:
            "0 10px 30px rgba(0,0,0,0.2)",
        }}
      >
        <img
          src={logoInstansi}
          alt="Logo"
          style={{
            width: 90,
            display: "block",
            margin: "0 auto 20px",
          }}
        />

        <h2 className="text-center text-2xl font-bold text-blue-700">
          Login Admin
        </h2>

        <p className="text-center text-gray-500 mb-6">
          Sistem E-Tamu Digital
        </p>

        <input
          type="text"
          placeholder="Username"
          value={username}
          onChange={(e) =>
            setUsername(e.target.value)
          }
          className="w-full border p-3 rounded-lg mb-4"
        />

        <input
          type="password"
          placeholder="Password"
          value={password}
          onChange={(e) =>
            setPassword(e.target.value)
          }
          className="w-full border p-3 rounded-lg mb-5"
        />

        <button
          onClick={handleLogin}
          className="w-full bg-blue-600 text-white p-3 rounded-lg"
        >
          Login
        </button>
      </div>
    </div>
  );
}