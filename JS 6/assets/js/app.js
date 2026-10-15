// Mengaktifkan menu navigasi responsif jika elemen menu tersedia.
function initNavToggle() {
  // Mengambil tombol menu dan elemen navigasinya.
  const navToggle = document.querySelector("#nav-toggle");
  const nav = document.querySelector("header nav");

  // Halaman tanpa navigasi tidak perlu menjalankan handler.
  if (!navToggle || !nav) return;

  // Kelas show mengikuti status checkbox menu.
  navToggle.addEventListener("change", function () {
    nav.classList.toggle("show", navToggle.checked);
  });
}
// Menangani klik hapus dengan delegasi agar tombol tabel dinamis juga berfungsi.
function initHapusConfirm() {
  // Satu listener pada dokumen menangkap klik dari tombol yang cocok.
  document.addEventListener("click", function (event) {
    // closest juga mengenali ikon/teks di dalam tombol hapus.
    const tombol = event.target.closest(".btn-hapus");

    // Abaikan klik yang bukan berasal dari tombol hapus.
    if (!tombol) return;

    // Cari baris data pemilik tombol.
    const baris = tombol.closest("tr");

    // Hapus baris hanya setelah pengguna menyetujui dialog.
    if (confirm("Apakah kamu yakin ingin menghapus data ini?")) {
      if (baris) {
        baris.remove();
      }
    }
  });
}

// Memfilter isi tabel saat kata kunci pencarian berubah.
function initTableFilter() {
  const searchBox = document.querySelector("#search-box");

  // Halaman tanpa kotak pencarian tidak memerlukan filter.
  if (!searchBox) return;

  // Ambil kata kunci dan baris terbaru setiap kali tombol keyboard dilepas.
  searchBox.addEventListener("keyup", function () {
    const keyword = searchBox.value.toLowerCase();
    const rows = document.querySelectorAll("table tbody tr");

    rows.forEach(function (row) {
      // Bandingkan kata kunci dengan seluruh teks pada satu baris.
      const text = row.textContent.toLowerCase();

      // Tampilkan kecocokan dan sembunyikan baris yang tidak cocok.
      if (text.includes(keyword)) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  });
}

// Mengganti pesan error input dengan pesan validasi yang baru.
function tampilkanError(input, pesan) {
  hapusError(input);

  // Buat elemen kecil untuk menampilkan keterangan kesalahan.
  const error = document.createElement("small");
  error.className = "error";
  error.textContent = pesan;

  // Tempatkan pesan tepat setelah input yang divalidasi.
  input.insertAdjacentElement("afterend", error);
}

// Menghapus pesan validasi lama dari satu input.
function hapusError(input) {
  const error = input.parentElement.querySelector(".error");

  // Lakukan penghapusan hanya jika pesan error ditemukan.
  if (error) {
    error.remove();
  }
}

// Memeriksa input wajib pada formulir pelanggan dan motor.
function initValidasiForm() {
  const form = document.querySelector("#form-tambah");

  // Keluar jika halaman ini tidak memiliki formulir tambah data.
  if (!form) return;

  // Validasi dilakukan sebelum browser mengirim formulir.
  form.addEventListener("submit", function (event) {
    // Status akhir berubah menjadi false jika ada input kosong.
    let valid = true;

    // Simpan referensi input agar bisa diperiksa dan diberi pesan.
    const nama = form.querySelector("#nama");
    const noPelanggan = form.querySelector("#no_pelanggan");
    const noKtp = form.querySelector("#no_ktp");
    const platNomor = form.querySelector("#plat_nomor");
    const merek = form.querySelector("#merek");
    const model = form.querySelector("#model");
    const tahun = form.querySelector("#tahun");
    const hargaSewa = form.querySelector("#harga_sewa_per_hari");

    // trim() mengabaikan spasi di awal/akhir sebelum mengecek kosong.
    if (nama && nama.value.trim() === "") {
      tampilkanError(nama, "Nama wajib diisi.");
      valid = false;
    } else if (nama) {
      hapusError(nama);
    }

    // Periksa nomor pelanggan; hapus error jika nilainya sudah diisi.
    if (noPelanggan && noPelanggan.value.trim() === "") {
      tampilkanError(noPelanggan, "No. pelanggan wajib diisi.");
      valid = false;
    } else if (noPelanggan) {
      hapusError(noPelanggan);
    }

    // Periksa nomor identitas pelanggan.
    if (noKtp && noKtp.value.trim() === "") {
      tampilkanError(noKtp, "No. KTP wajib diisi.");
      valid = false;
    } else if (noKtp) {
      hapusError(noKtp);
    }

    // Periksa nomor polisi motor.
    if (platNomor && platNomor.value.trim() === "") {
      tampilkanError(platNomor, "Plat nomor wajib diisi.");
      valid = false;
    } else if (platNomor) {
      hapusError(platNomor);
    }

    // Periksa merek motor.
    if (merek && merek.value.trim() === "") {
      tampilkanError(merek, "Merek wajib diisi.");
      valid = false;
    } else if (merek) {
      hapusError(merek);
    }

    // Periksa model motor.
    if (model && model.value.trim() === "") {
      tampilkanError(model, "Model wajib diisi.");
      valid = false;
    } else if (model) {
      hapusError(model);
    }

    // Periksa tahun motor.
    if (tahun && tahun.value.trim() === "") {
      tampilkanError(tahun, "Tahun terbit wajib diisi.");
      valid = false;
    } else if (tahun) {
      hapusError(tahun);
    }

    // Periksa tarif sewa harian.
    if (hargaSewa && hargaSewa.value.trim() === "") {
      tampilkanError(hargaSewa, "Harga sewa per hari wajib diisi.");
      valid = false;
    } else if (hargaSewa) {
      hapusError(hargaSewa);
    }

    if (!valid) {
      // Menahan pengiriman jika minimal satu input wajib tidak valid.
      event.preventDefault();
    }
  });
}

// Menyiapkan login petugas/pelanggan dan fitur bantu autentikasi demo.
function initLogin() {
  // Ambil elemen antarmuka yang digunakan proses login.
  const form = document.querySelector("#form-login");
  const password = document.querySelector("#password");
  const toggle = document.querySelector(".password-toggle");
  const message = document.querySelector("#login-message");
  const userLogin = document.querySelector("#user-login");
  const userForm = document.querySelector("#form-user-login");
  const userMessage = document.querySelector("#user-login-message");
  // Ambil akun demo dari localStorage; gunakan akun contoh bila belum ada.
  const registeredAccounts = JSON.parse(
    localStorage.getItem("rentalAccounts") || "null",
  ) || {
    "pelanggan@gmail.com": "pelanggan123",
  };

  function saveAccounts() {
    // Simpan perubahan akun sebagai teks JSON di browser ini.
    localStorage.setItem("rentalAccounts", JSON.stringify(registeredAccounts));
  }

  // Jangan pasang fitur login bila elemen form utama tidak tersedia.
  if (!form || !password || !toggle || !message) return;

  // Tombol mode menukar formulir login petugas dan pelanggan.
  document.querySelectorAll(".login-mode").forEach(function (modeButton) {
    modeButton.addEventListener("click", function () {
      // Tentukan mode berdasar atribut data-mode pada tombol.
      const isUserMode = modeButton.dataset.mode === "user";
      // Perbarui status aktif dan aksesibilitas semua tombol mode.
      document.querySelectorAll(".login-mode").forEach(function (button) {
        button.classList.toggle("active", button === modeButton);
        button.setAttribute(
          "aria-selected",
          button === modeButton ? "true" : "false",
        );
      });
      // Tampilkan formulir dan teks yang sesuai dengan mode terpilih.
      form.hidden = isUserMode;
      userLogin.hidden = !isUserMode;
      document.querySelector("#login-eyebrow").textContent = isUserMode
        ? "AKSES PELANGGAN"
        : "SELAMAT DATANG KEMBALI";
      document.querySelector("#login-title").textContent = isUserMode
        ? "Mulai perjalananmu"
        : "Masuk ke ruang kerja";
      document.querySelector("#login-description").textContent = isUserMode
        ? "Masuk untuk mencari motor dan mengatur penyewaanmu."
        : "Gunakan akun petugas untuk melanjutkan pengelolaan rental.";
      document.querySelector("#demo-account").hidden = isUserMode;
    });
  });

  // Tombol mata berganti antara teks terlihat dan kata sandi tersembunyi.
  toggle.addEventListener("click", function () {
    const isPassword = password.type === "password";
    password.type = isPassword ? "text" : "password";
    toggle.innerHTML = isPassword
      ? '<i class="bi bi-eye-slash"></i>'
      : '<i class="bi bi-eye"></i>';
    toggle.setAttribute(
      "aria-label",
      isPassword ? "Sembunyikan kata sandi" : "Tampilkan kata sandi",
    );
  });

  // Periksa kredensial petugas saat formulir utama dikirim.
  form.addEventListener("submit", function (event) {
    // Login demo ditangani di halaman ini, bukan dikirim ke server.
    event.preventDefault();
    const username = form.querySelector("#username").value.trim();

    // Cocokkan username dan kata sandi contoh.
    if (username === "petugas" && password.value === "rental123") {
      // Simpan identitas sementara selama tab browser terbuka.
      sessionStorage.setItem("rentalUser", username);
      message.className = "login-message success";
      message.textContent = "Berhasil masuk. Membuka dashboard...";
      // Buka halaman daftar motor setelah pesan berhasil terlihat sebentar.
      window.setTimeout(function () {
        window.location.href = "dashboard.html";
      }, 450);
      return;
    }

    // Tampilkan pesan jika salah satu kredensial tidak cocok.
    message.className = "login-message error";
    message.textContent = "Username atau kata sandi belum sesuai.";
  });

  const userPassword = document.querySelector("#user-password");
  const userToggle = document.querySelector(".user-password-toggle");
  // Tampilkan/sembunyikan kata sandi pada formulir pelanggan.
  userToggle.addEventListener("click", function () {
    const isPassword = userPassword.type === "password";
    userPassword.type = isPassword ? "text" : "password";
    userToggle.innerHTML = isPassword
      ? '<i class="bi bi-eye-slash"></i>'
      : '<i class="bi bi-eye"></i>';
  });

  const googleModal = document.querySelector("#google-modal");
  const closeGoogleModal = document.querySelector("#google-modal-close");
  const googleEmailForm = document.querySelector("#google-email-form");
  const googleEmail = document.querySelector("#google-email");
  const googleEmailMessage = document.querySelector("#google-email-message");

  // Tombol Google membuka modal simulasi input email.
  document
    .querySelector("#google-login")
    .addEventListener("click", function () {
      googleModal.hidden = false;
    });

  // Tutup modal melalui tombol tutup.
  closeGoogleModal.addEventListener("click", function () {
    googleModal.hidden = true;
  });

  // Klik pada latar modal juga menutupnya, tetapi klik isi modal tidak.
  googleModal.addEventListener("click", function (event) {
    if (event.target === googleModal) {
      googleModal.hidden = true;
    }
  });

  // Validasi alamat Gmail pada alur login Google simulasi.
  googleEmailForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const email = googleEmail.value.trim();

    // Tolak alamat yang bukan Gmail sebelum membuat sesi demo.
    if (!email.toLowerCase().endsWith("@gmail.com")) {
      googleEmailMessage.className = "login-message error";
      googleEmailMessage.textContent = "Masukkan alamat Gmail yang valid.";
      return;
    }

    // Simpan email sementara dan arahkan pengguna ke katalog.
    sessionStorage.setItem("rentalUser", email);
    googleModal.hidden = true;
    userMessage.className = "login-message success";
    userMessage.textContent =
      "Akun " + email + " digunakan. Membuka katalog motor...";
    window.setTimeout(function () {
      window.location.href = "buku/list.html";
    }, 450);
  });

  // Login pelanggan mencocokkan email dan kata sandi di localStorage.
  userForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const contact = document.querySelector("#user-contact").value.trim();
    // Normalisasi email agar pencarian tidak peka huruf kapital.
    const accountPassword = registeredAccounts[contact.toLowerCase()];

    // Masuk hanya bila email terdaftar dan kata sandi cocok.
    if (accountPassword && accountPassword === userPassword.value) {
      sessionStorage.setItem("rentalUser", contact);
      userMessage.className = "login-message success";
      userMessage.textContent = "Berhasil masuk. Membuka katalog motor...";
      window.setTimeout(function () {
        window.location.href = "buku/list.html";
      }, 450);
      return;
    }

    // Bedakan pesan email belum terdaftar dan kata sandi yang salah.
    userMessage.className = "login-message error";
    userMessage.textContent = accountPassword
      ? "Password salah. Silakan coba lagi atau gunakan Lupa password."
      : "Email belum terdaftar. Periksa kembali email atau daftar terlebih dahulu.";
  });

  const forgotModal = document.querySelector("#forgot-modal");
  const forgotForm = document.querySelector("#forgot-form");
  const forgotEmail = document.querySelector("#forgot-email");
  const forgotMessage = document.querySelector("#forgot-message");
  const newPassword = document.querySelector("#new-password");
  const newPasswordWrap = document.querySelector(".new-password-wrap");
  const newPasswordLabel = document.querySelector(".new-password-label");
  const forgotSubmit = document.querySelector("#forgot-submit");

  // Buka dialog lupa kata sandi dan arahkan fokus ke input email.
  document
    .querySelector("#forgot-password-link")
    .addEventListener("click", function (event) {
      event.preventDefault();
      forgotModal.hidden = false;
      forgotEmail.focus();
    });

  // Tutup dialog lupa kata sandi melalui tombol tutup.
  document
    .querySelector("#forgot-modal-close")
    .addEventListener("click", function () {
      forgotModal.hidden = true;
    });

  // Klik di luar konten dialog juga menutup modal.
  forgotModal.addEventListener("click", function (event) {
    if (event.target === forgotModal) {
      forgotModal.hidden = true;
    }
  });

  // Tombol mata mengubah visibilitas kata sandi baru.
  document
    .querySelector(".reset-password-toggle")
    .addEventListener("click", function (event) {
      const button = event.currentTarget;
      const isPassword = newPassword.type === "password";
      newPassword.type = isPassword ? "text" : "password";
      button.innerHTML = isPassword
        ? '<i class="bi bi-eye-slash"></i>'
        : '<i class="bi bi-eye"></i>';
    });

  // Alur reset demo: cek akun, minta kata sandi baru, lalu simpan.
  forgotForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const email = forgotEmail.value.trim().toLowerCase();

    // Pastikan email memang sudah tersimpan sebelum reset dilanjutkan.
    if (!registeredAccounts[email]) {
      forgotMessage.className = "login-message error";
      forgotMessage.textContent = "Email belum terdaftar di sistem.";
      return;
    }

    // Tahap pertama menampilkan input kata sandi baru.
    if (newPasswordWrap.hidden) {
      newPasswordWrap.hidden = false;
      newPasswordLabel.hidden = false;
      forgotSubmit.innerHTML =
        'Simpan password baru <i class="bi bi-check2"></i>';
      forgotMessage.className = "login-message success";
      forgotMessage.textContent =
        "Email terdaftar. Silakan buat password baru.";
      newPassword.focus();
      return;
    }

    // Tolak kata sandi baru yang kurang dari enam karakter.
    if (newPassword.value.length < 6) {
      forgotMessage.className = "login-message error";
      forgotMessage.textContent = "Password baru minimal 6 karakter.";
      return;
    }

    // Ganti kata sandi di data lokal lalu simpan perubahan.
    registeredAccounts[email] = newPassword.value;
    saveAccounts();
    forgotMessage.className = "login-message success";
    forgotMessage.textContent =
      "Password berhasil diubah. Silakan masuk dengan password baru.";
    forgotSubmit.disabled = true;
  });
}

// Inisialisasi semua fitur setelah dokumen HTML siap digunakan.
document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initHapusConfirm();
  initTableFilter();
  initValidasiForm();
  initLogin();
});
