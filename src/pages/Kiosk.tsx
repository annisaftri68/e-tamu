import { useEffect, useState } from "react";
import API from "../services/api";

export default function Kiosk() {
  const [departments, setDepartments] = useState([]);

  const [form, setForm] = useState({
    nama: "",
    nik: "",
    instansi: "",
    no_hp: "",
    keperluan: "",
    department_id: "",
  });

  useEffect(() => {
    getDepartments();
  }, []);

  const getDepartments = async () => {
    try {
      const response = await API.get(
        "/departments/get.php"
      );

      setDepartments(response.data);
    } catch (error) {
      console.log(error);
    }
  };

  const handleChange = (
    e: React.ChangeEvent<
      HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement
    >
  ) => {
    setForm({
      ...form,
      [e.target.name]: e.target.value,
    });
  };

  const saveVisitor = async (
    e: React.FormEvent
  ) => {
    e.preventDefault();

    try {
      const response =
        await API.post(
          "/visitors/create.php",
          form
        );

      alert(response.data.message);

      setForm({
        nama: "",
        nik: "",
        instansi: "",
        no_hp: "",
        keperluan: "",
        department_id: "",
      });
    } catch (error) {
      console.log(error);
      alert("Gagal menyimpan data.");
    }
  };

  return (
    <div className="max-w-3xl mx-auto p-8">
      <h1 className="text-3xl font-bold mb-6">
        Buku Tamu Digital
      </h1>

      <form
        onSubmit={saveVisitor}
        className="space-y-4"
      >
        <input
          name="nama"
          value={form.nama}
          onChange={handleChange}
          placeholder="Nama Lengkap"
          className="w-full border p-3 rounded"
          required
        />

        <input
          name="nik"
          value={form.nik}
          onChange={handleChange}
          placeholder="NIK"
          className="w-full border p-3 rounded"
          required
        />

        <input
          name="instansi"
          value={form.instansi}
          onChange={handleChange}
          placeholder="Instansi"
          className="w-full border p-3 rounded"
        />

        <input
          name="no_hp"
          value={form.no_hp}
          onChange={handleChange}
          placeholder="Nomor HP"
          className="w-full border p-3 rounded"
        />

        <textarea
          name="keperluan"
          value={form.keperluan}
          onChange={handleChange}
          placeholder="Keperluan"
          className="w-full border p-3 rounded"
          rows={4}
        />

        <select
          name="department_id"
          value={form.department_id}
          onChange={handleChange}
          className="w-full border p-3 rounded"
          required
        >
          <option value="">
            Pilih Bidang Tujuan
          </option>

          {departments.map((d: any) => (
            <option
              key={d.id}
              value={d.id}
            >
              {d.nama_bidang}
            </option>
          ))}
        </select>

        <button
          type="submit"
          className="bg-blue-600 text-white px-6 py-3 rounded"
        >
          Daftar Kunjungan
        </button>
      </form>
    </div>
  );
}