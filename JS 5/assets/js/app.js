// Menghubungkan tombol menu dengan navigasi untuk tampilan layar kecil.
function initNavToggle() {
  // Mencari kontrol menu dan elemen navigasi pada halaman.
  const navToggle = document.querySelector("#nav-toggle");
  const nav = document.querySelector("header nav");

  // Berhenti jika halaman ini tidak memiliki salah satu elemen tersebut.
  if (!navToggle || !nav) return;

  // Mengubah status menu saat nilai checkbox berubah.
  navToggle.addEventListener("change", function () {
    nav.classList.toggle("show", navToggle.checked);
  });
}
// Meminta konfirmasi sebelum menghapus baris tabel.
function initHapusConfirm() {
  // Mengambil semua tombol hapus yang sudah ada saat halaman dimuat.
  const tombolHapus = document.querySelectorAll(".btn-hapus");

  // Memasang aksi klik pada setiap tombol.
  tombolHapus.forEach(function (tombol) {
    tombol.addEventListener("click", function () {
      // Menemukan baris tabel yang memuat tombol ini.
      const baris = tombol.closest("tr");

      // Baris hanya dihapus jika pengguna menyetujui konfirmasi.
      if (confirm("Apakah kamu yakin ingin menghapus data ini?")) {
        if (baris) {
          baris.remove();
        }
      }
    });
  });
}

// Menyaring baris tabel berdasarkan teks yang diketik pengguna.
function initTableFilter() {
  // Mencari kotak pencarian dan baris data yang akan difilter.
  const searchBox = document.querySelector("#search-box");
  const rows = document.querySelectorAll("table tbody tr");

  // Tidak memasang fitur bila kotak pencarian atau baris tidak ditemukan.
  if (!searchBox || rows.length === 0) return;

  // Memperbarui tampilan setiap kali tombol keyboard dilepas.
  searchBox.addEventListener("keyup", function () {
    // Huruf kecil membuat pencarian tidak membedakan kapital.
    const keyword = searchBox.value.toLowerCase();

    rows.forEach(function (row) {
      // Teks semua kolom pada baris dibandingkan dengan kata kunci.
      const text = row.textContent.toLowerCase();

      // Baris yang cocok ditampilkan; baris lain disembunyikan.
      if (text.includes(keyword)) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  });
}

// Menampilkan pesan validasi tepat setelah input yang bermasalah.
function tampilkanError(input, pesan) {
  // Menghapus pesan lama agar tidak muncul berulang.
  hapusError(input);

  // Membuat elemen pesan baru dan mengisi teksnya.
  const error = document.createElement("small");
  error.className = "error";
  error.textContent = pesan;

  // Menyisipkan pesan setelah elemen input.
  input.insertAdjacentElement("afterend", error);
}

// Menghapus pesan validasi milik satu input jika ada.
function hapusError(input) {
  const error = input.parentElement.querySelector(".error");

  // Elemen dihapus hanya jika memang ditemukan.
  if (error) {
    error.remove();
  }
}

// Memeriksa input wajib sebelum formulir dikirim.
function initValidasiForm() {
  // Mengambil formulir tambah; halaman lain mungkin tidak memilikinya.
  const form = document.querySelector("#form-tambah");

  // Tidak memasang validasi bila formulir tidak tersedia.
  if (!form) return;

  // Pemeriksaan dijalankan saat pengguna mengirim formulir.
  form.addEventListener("submit", function (event) {
    // Asumsikan benar dahulu; setiap input kosong akan mengubahnya.
    let valid = true;

    // Mengambil semua input yang mungkin wajib pada formulir anggota/buku.
    const nama = form.querySelector("#nama");
    const noAnggota = form.querySelector("#no_anggota");
    const judul = form.querySelector("#judul");
    const pengarang = form.querySelector("#pengarang");
    const tahun = form.querySelector("#tahun");
    const stok = form.querySelector("#stok");

    // trim() menganggap input berisi spasi saja sebagai kosong.
    if (nama && nama.value.trim() === "") {
      tampilkanError(nama, "Nama wajib diisi.");
      valid = false;
    } else if (nama) {
      hapusError(nama);
    }

    if (noAnggota && noAnggota.value.trim() === "") {
      tampilkanError(noAnggota, "No. Anggota wajib diisi.");
      valid = false;
    } else if (noAnggota) {
      hapusError(noAnggota);
    }

    if (judul && judul.value.trim() === "") {
      tampilkanError(judul, "Judul wajib diisi.");
      valid = false;
    } else if (judul) {
      hapusError(judul);
    }

    if (pengarang && pengarang.value.trim() === "") {
      tampilkanError(pengarang, "Pengarang wajib diisi.");
      valid = false;
    } else if (pengarang) {
      hapusError(pengarang);
    }

    if (tahun && tahun.value.trim() === "") {
      tampilkanError(tahun, "Tahun terbit wajib diisi.");
      valid = false;
    } else if (tahun) {
      hapusError(tahun);
    }

    if (stok && stok.value.trim() === "") {
      tampilkanError(stok, "Stok wajib diisi.");
      valid = false;
    } else if (stok) {
      hapusError(stok);
    }

    if (!valid) {
      // Mencegah formulir dikirim jika ada input wajib yang kosong.
      event.preventDefault();
    }
  });
}
// Menunggu HTML selesai dibuat sebelum mengaktifkan fitur halaman.
document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initHapusConfirm();
  initTableFilter();
  initValidasiForm();
});
