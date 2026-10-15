async function muatRingkasan() {
  const pesan = document.querySelector("#dashboard-message");
  const totalMotor = document.querySelector("#total-motor");
  const totalPelanggan = document.querySelector("#total-pelanggan");
  const sedangDisewa = document.querySelector("#sedang-disewa");

  try {
    const [motorResponse, pelangganResponse] = await Promise.all([
      fetch("data/buku.json", { cache: "no-store" }),
      fetch("data/anggota.json", { cache: "no-store" }),
    ]);

    if (!motorResponse.ok || !pelangganResponse.ok) {
      throw new Error("Gagal mengambil data motor atau pelanggan.");
    }

    const [motor, pelanggan] = await Promise.all([
      motorResponse.json(),
      pelangganResponse.json(),
    ]);

    if (!Array.isArray(motor) || !Array.isArray(pelanggan)) {
      throw new Error("Format data motor atau pelanggan tidak valid.");
    }

    totalMotor.textContent = motor.length;
    totalPelanggan.textContent = pelanggan.length;
    sedangDisewa.textContent = motor.filter(
      (item) => item.status?.toLowerCase() === "disewa",
    ).length;
    pesan.textContent = "Ringkasan berhasil diperbarui dari data terbaru.";
  } catch (error) {
    console.error("Ringkasan sistem gagal dimuat.", error);
    pesan.textContent =
      "Ringkasan gagal dimuat. Jalankan halaman melalui server lokal dan periksa file data JSON motor dan pelanggan.";
  }
}

document.addEventListener("DOMContentLoaded", muatRingkasan);
