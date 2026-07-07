import { Bell, UserCircle } from "lucide-react";

export default function Navbar() {
  return (
    <header
      className="
      h-20
      bg-white
      shadow-sm
      px-8
      flex
      items-center
      justify-between
      "
    >
      <div>
        <h2 className="text-2xl font-bold text-gray-800">
          Dashboard Admin
        </h2>

        <p className="text-gray-500 text-sm">
          BKPSDM Kabupaten Tegal
        </p>
      </div>

      <div className="flex items-center gap-5">
        <Bell
          className="text-gray-600"
          size={22}
        />

        <div className="flex items-center gap-2">
          <UserCircle
            size={35}
            className="text-blue-700"
          />

          <div>
            <p className="font-semibold">
              Administrator
            </p>

            <p className="text-xs text-gray-500">
              Admin BKPSDM
            </p>
          </div>
        </div>
      </div>
    </header>
  );
}