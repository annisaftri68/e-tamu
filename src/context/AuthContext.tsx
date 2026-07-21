import { createContext, useState, useEffect } from "react";
import API from "../services/api";

export const AuthContext = createContext<any>(null);

export const AuthProvider = ({
  children,
}: {
  children: React.ReactNode;
}) => {
  const [user, setUser] = useState(
    JSON.parse(localStorage.getItem("user") || "null")
  );
  const [loading, setLoading] = useState(true);

  // Cek apakah user masih login saat aplikasi dimuat
  useEffect(() => {
    const storedUser = localStorage.getItem("user");
    if (storedUser) {
      setUser(JSON.parse(storedUser));
    }
    setLoading(false);
  }, []);

  const login = async (username: string, password: string) => {
    try {
      const response = await API.post("/login", { username, password });

      if (response.data.success) {
        const userData = response.data.user;
        // Simpan user data ke localStorage
        localStorage.setItem("user", JSON.stringify(userData));
        setUser(userData);
        return { success: true, user: userData };
      }
    } catch (error: any) {
      const message =
        error.response?.data?.message || "Username/NIP atau Password salah!";
      return { success: false, message };
    }

    return { success: false, message: "Terjadi kesalahan, coba lagi." };
  };

  const logout = () => {
    localStorage.removeItem("user");
    setUser(null);
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        setUser,
        login,
        logout,
        loading,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};
