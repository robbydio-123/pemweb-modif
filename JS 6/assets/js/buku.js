// Data motor cadangan menjaga tabel tetap terisi jika JSON gagal dimuat.
const dataBukuLokal = [
  {
    plat_nomor: "N 1234 AB",
    merek: "Honda",
    model: "Vario 160",
    tahun: 2024,
    warna: "Hitam",
    jenis: "Matic",
    kapasitas_cc: 160,
    harga_sewa_per_hari: 85000,
    status: "Tersedia",
  },
  {
    plat_nomor: "N 2345 CD",
    merek: "Yamaha",
    model: "NMAX",
    tahun: 2023,
    warna: "Biru",
    jenis: "Matic",
    kapasitas_cc: 155,
    harga_sewa_per_hari: 120000,
    status: "Tersedia",
  },
  {
    plat_nomor: "N 3456 EF",
    merek: "Honda",
    model: "Beat Street",
    tahun: 2022,
    warna: "Merah",
    jenis: "Matic",
    kapasitas_cc: 110,
    harga_sewa_per_hari: 70000,
    status: "Disewa",
  },
];

function tampilkanBuku(tbody, dataBuku) {
  // Hapus isi lama sebelum membuat ulang baris motor.
  tbody.innerHTML = "";

  // Mengubah setiap objek motor menjadi baris HTML.
  dataBuku.forEach(function (buku) {
    const row = document.createElement("tr");
    // Gabungkan merek dan model untuk label motor.
    const motor = `${buku.merek} ${buku.model}`;
    // URL-encode parameter agar spasi dan karakter khusus aman di URL.
    const linkPesan = `../penyewaan/tambah.html?plat_nomor=${encodeURIComponent(buku.plat_nomor)}&motor=${encodeURIComponent(motor)}&harga=${encodeURIComponent(buku.harga_sewa_per_hari)}`;
    // Hanya motor berstatus tersedia yang mendapat tautan pemesanan.
    const aksi =
      buku.status === "Tersedia"
        ? `<a class="btn-pesan" href="${linkPesan}"><i class="bi bi-calendar-check"></i> Pesan</a>`
        : `<span class="aksi-nonaktif">Tidak tersedia</span>`;

    // Masukkan nilai motor dan aksi ke dalam kolom tabel.
    row.innerHTML = `
            <td>${buku.plat_nomor}</td>
            <td>${motor}</td>
            <td>${buku.tahun}</td>
            <td>${buku.jenis}</td>
            <td>Rp${Number(buku.harga_sewa_per_hari).toLocaleString("id-ID")}</td>
            <td><span class="status-${buku.status.toLowerCase()}">${buku.status}</span></td>
            <td>${aksi}</td>
        `;

    // Masukkan baris ke badan tabel.
    tbody.appendChild(row);
  });
}

// Mengambil data motor dari JSON dan mengisi tabel.
async function muatDaftarBuku() {
  const tbody = document.querySelector("#tabel-buku");

  // Keluar lebih awal jika halaman ini tidak memuat tabel motor.
  if (!tbody) return;

  // Tampilkan status sementara saat permintaan data berjalan.
  tbody.innerHTML = `
        <tr>
            <td colspan="7">Memuat data...</td>
        </tr>
    `;

  try {
    // Meminta data motor dari berkas JSON relatif terhadap halaman.
    const response = await fetch("../data/buku.json");

    // Lempar error untuk respons HTTP yang gagal.
    if (!response.ok) {
      throw new Error("Gagal mengambil data motor.");
    }

    // Baca JSON, lalu kirim array datanya ke fungsi pembuat tabel.
    const dataBuku = await response.json();
    tampilkanBuku(tbody, dataBuku);
  } catch (error) {
    // Catat kegagalan dan gunakan data cadangan agar halaman tetap berguna.
    console.warn("JSON motor tidak dapat dimuat, memakai data lokal.", error);
    tampilkanBuku(tbody, dataBukuLokal);
  }
}

// Memulai proses setelah browser selesai membuat DOM.
document.addEventListener("DOMContentLoaded", muatDaftarBuku);
