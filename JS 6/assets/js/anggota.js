// Data cadangan dipakai bila berkas JSON tidak berhasil dibaca.
const dataAnggotaCadangan = [
  {
    no_pelanggan: "P001",
    nama: "Siti Aminah",
    no_ktp: "3573012345670001",
    alamat: "Lowokwaru, Malang",
    no_hp: "081234567890",
  },
  {
    no_pelanggan: "P002",
    nama: "Budi Santoso",
    no_ktp: "3573012345670002",
    alamat: "Tlogomas, Malang",
    no_hp: "081345678901",
  },
  {
    no_pelanggan: "P003",
    nama: "Citra Lestari",
    no_ktp: "3573012345670003",
    alamat: "Dinoyo, Malang",
    no_hp: "081456789012",
  },
  {
    no_pelanggan: "P004",
    nama: "Dimas Pratama",
    no_ktp: "3573012345670004",
    alamat: "Merjosari, Malang",
    no_hp: "081567890123",
  },
];

function tampilkanAnggota(tbody, dataAnggota) {
  // Mengosongkan isi tabel agar data tidak terduplikasi.
  tbody.innerHTML = "";

  // Mengubah setiap objek pelanggan menjadi satu baris tabel.
  dataAnggota.forEach(function (anggota) {
    const row = document.createElement("tr");

    // Menyusun kolom pelanggan dan tombol aksi ke dalam baris.
    row.innerHTML = `
            <td>${anggota.no_pelanggan}</td>
            <td>${anggota.nama}</td>
            <td>${anggota.no_ktp}</td>
            <td>${anggota.alamat}</td>
            <td>${anggota.no_hp}</td>
            <td>
                <button type="button">Edit</button>
                <button type="button" class="btn-hapus">Hapus</button>
            </td>
        `;

    // Menambahkan baris yang sudah dibuat ke tabel.
    tbody.appendChild(row);
  });
}

// Mengambil data pelanggan dari JSON lalu menampilkannya.
async function muatDaftarAnggota() {
  const tbody = document.querySelector("#tabel-anggota");
  // Halaman lain mungkin tidak memiliki tabel pelanggan.
  if (!tbody) return;

  // Memberi tahu pengguna bahwa data sedang dimuat.
  tbody.innerHTML = `
        <tr>
            <td colspan="6">Memuat data...</td>
        </tr>
    `;

  try {
    // Meminta data melalui HTTP; fetch memerlukan server lokal seperti Laragon.
    const response = await fetch("../data/anggota.json");

    // Ubah respons gagal menjadi error agar ditangani oleh catch.
    if (!response.ok) {
      throw new Error("Gagal mengambil data pelanggan.");
    }

    // Mengubah isi JSON menjadi array JavaScript.
    const dataAnggota = await response.json();
    tampilkanAnggota(tbody, dataAnggota);
  } catch (error) {
    // Jika permintaan gagal, tampilkan data cadangan dan catat penyebabnya.
    console.warn(
      "JSON pelanggan tidak dapat dimuat, memakai data cadangan.",
      error,
    );
    tampilkanAnggota(tbody, dataAnggotaCadangan);
  }
}
// Memulai pemuatan setelah struktur halaman selesai dibaca browser.
document.addEventListener("DOMContentLoaded", muatDaftarAnggota);
