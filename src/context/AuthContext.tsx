import { createContext, useState } from "react";

export const AuthContext = createContext<any>(null);

export const AuthProvider = ({
  children,
}: {
  children: React.ReactNode;
}) => {
  const [user, setUser] = useState(
    JSON.parse(localStorage.getItem("user") || "null")
  );

  return (
    <AuthContext.Provider
      value={{
        user,
        setUser,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};