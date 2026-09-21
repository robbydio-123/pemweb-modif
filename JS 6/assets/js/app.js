function initNavToggle() {
    const navToggle = document.querySelector("#nav-toggle");
    const nav = document.querySelector("header nav");

    if (!navToggle || !nav) return;

    navToggle.addEventListener("change", function () {
        nav.classList.toggle("show", navToggle.checked);
    });
}
function initHapusConfirm() {
    document.addEventListener("click", function (event) {
        const tombol = event.target.closest(".btn-hapus");

        if (!tombol) return;

        const baris = tombol.closest("tr");

        if (confirm("Apakah kamu yakin ingin menghapus data ini?")) {
            if (baris) {
                baris.remove();
            }
        }
    });
}

function initTableFilter() {
    const searchBox = document.querySelector("#search-box");

    if (!searchBox) return;

    searchBox.addEventListener("keyup", function () {
        const keyword = searchBox.value.toLowerCase();
        const rows = document.querySelectorAll("table tbody tr");

        rows.forEach(function (row) {
            const text = row.textContent.toLowerCase();

            if (text.includes(keyword)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
}

function tampilkanError(input, pesan) {
    hapusError(input);

    const error = document.createElement("small");
    error.className = "error";
    error.textContent = pesan;

    input.insertAdjacentElement("afterend", error);
}

function hapusError(input) {
    const error = input.parentElement.querySelector(".error");

    if (error) {
        error.remove();
    }
}

function initValidasiForm() {
    const form = document.querySelector("#form-tambah");

    if (!form) return;

    form.addEventListener("submit", function (event) {
        let valid = true;

        const nama = form.querySelector("#nama");
        const noPelanggan = form.querySelector("#no_pelanggan");
        const noKtp = form.querySelector("#no_ktp");
        const platNomor = form.querySelector("#plat_nomor");
        const merek = form.querySelector("#merek");
        const model = form.querySelector("#model");
        const tahun = form.querySelector("#tahun");
        const hargaSewa = form.querySelector("#harga_sewa_per_hari");

        if (nama && nama.value.trim() === "") {
            tampilkanError(nama, "Nama wajib diisi.");
            valid = false;
        } else if (nama) {
            hapusError(nama);
        }

        if (noPelanggan && noPelanggan.value.trim() === "") {
            tampilkanError(noPelanggan, "No. pelanggan wajib diisi.");
            valid = false;
        } else if (noPelanggan) {
            hapusError(noPelanggan);
        }

        if (noKtp && noKtp.value.trim() === "") {
            tampilkanError(noKtp, "No. KTP wajib diisi.");
            valid = false;
        } else if (noKtp) {
            hapusError(noKtp);
        }

        if (platNomor && platNomor.value.trim() === "") {
            tampilkanError(platNomor, "Plat nomor wajib diisi.");
            valid = false;
        } else if (platNomor) {
            hapusError(platNomor);
        }

        if (merek && merek.value.trim() === "") {
            tampilkanError(merek, "Merek wajib diisi.");
            valid = false;
        } else if (merek) {
            hapusError(merek);
        }

        if (model && model.value.trim() === "") {
            tampilkanError(model, "Model wajib diisi.");
            valid = false;
        } else if (model) {
            hapusError(model);
        }

        if (tahun && tahun.value.trim() === "") {
            tampilkanError(tahun, "Tahun terbit wajib diisi.");
            valid = false;
        } else if (tahun) {
            hapusError(tahun);
        }

        if (hargaSewa && hargaSewa.value.trim() === "") {
            tampilkanError(hargaSewa, "Harga sewa per hari wajib diisi.");
            valid = false;
        } else if (hargaSewa) {
            hapusError(hargaSewa);
        }

        if (!valid) {
            event.preventDefault();
        }
    });
}

function initLogin() {
    const form = document.querySelector("#form-login");
    const password = document.querySelector("#password");
    const toggle = document.querySelector(".password-toggle");
    const message = document.querySelector("#login-message");
    const userLogin = document.querySelector("#user-login");
    const userForm = document.querySelector("#form-user-login");
    const userMessage = document.querySelector("#user-login-message");
    const registeredAccounts = JSON.parse(localStorage.getItem("rentalAccounts") || "null") || {
        "pelanggan@gmail.com": "pelanggan123"
    };

    function saveAccounts() {
        localStorage.setItem("rentalAccounts", JSON.stringify(registeredAccounts));
    }

    if (!form || !password || !toggle || !message) return;

    document.querySelectorAll(".login-mode").forEach(function (modeButton) {
        modeButton.addEventListener("click", function () {
            const isUserMode = modeButton.dataset.mode === "user";
            document.querySelectorAll(".login-mode").forEach(function (button) {
                button.classList.toggle("active", button === modeButton);
                button.setAttribute("aria-selected", button === modeButton ? "true" : "false");
            });
            form.hidden = isUserMode;
            userLogin.hidden = !isUserMode;
            document.querySelector("#login-eyebrow").textContent = isUserMode ? "AKSES PELANGGAN" : "SELAMAT DATANG KEMBALI";
            document.querySelector("#login-title").textContent = isUserMode ? "Mulai perjalananmu" : "Masuk ke ruang kerja";
            document.querySelector("#login-description").textContent = isUserMode ? "Masuk untuk mencari motor dan mengatur penyewaanmu." : "Gunakan akun petugas untuk melanjutkan pengelolaan rental.";
            document.querySelector("#demo-account").hidden = isUserMode;
        });
    });

    toggle.addEventListener("click", function () {
        const isPassword = password.type === "password";
        password.type = isPassword ? "text" : "password";
        toggle.innerHTML = isPassword ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
        toggle.setAttribute("aria-label", isPassword ? "Sembunyikan kata sandi" : "Tampilkan kata sandi");
    });

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        const username = form.querySelector("#username").value.trim();

        if (username === "petugas" && password.value === "rental123") {
            sessionStorage.setItem("rentalUser", username);
            message.className = "login-message success";
            message.textContent = "Berhasil masuk. Membuka dashboard...";
            window.setTimeout(function () {
                window.location.href = "buku/list.html";
            }, 450);
            return;
        }

        message.className = "login-message error";
        message.textContent = "Username atau kata sandi belum sesuai.";
    });

    const userPassword = document.querySelector("#user-password");
    const userToggle = document.querySelector(".user-password-toggle");
    userToggle.addEventListener("click", function () {
        const isPassword = userPassword.type === "password";
        userPassword.type = isPassword ? "text" : "password";
        userToggle.innerHTML = isPassword ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
    });

    const googleModal = document.querySelector("#google-modal");
    const closeGoogleModal = document.querySelector("#google-modal-close");
    const googleEmailForm = document.querySelector("#google-email-form");
    const googleEmail = document.querySelector("#google-email");
    const googleEmailMessage = document.querySelector("#google-email-message");

    document.querySelector("#google-login").addEventListener("click", function () {
        googleModal.hidden = false;
    });

    closeGoogleModal.addEventListener("click", function () {
        googleModal.hidden = true;
    });

    googleModal.addEventListener("click", function (event) {
        if (event.target === googleModal) {
            googleModal.hidden = true;
        }
    });

    googleEmailForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const email = googleEmail.value.trim();

        if (!email.toLowerCase().endsWith("@gmail.com")) {
            googleEmailMessage.className = "login-message error";
            googleEmailMessage.textContent = "Masukkan alamat Gmail yang valid.";
            return;
        }

        sessionStorage.setItem("rentalUser", email);
        googleModal.hidden = true;
        userMessage.className = "login-message success";
        userMessage.textContent = "Akun " + email + " digunakan. Membuka katalog motor...";
        window.setTimeout(function () {
            window.location.href = "buku/list.html";
        }, 450);
    });

    userForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const contact = document.querySelector("#user-contact").value.trim();
        const accountPassword = registeredAccounts[contact.toLowerCase()];

        if (accountPassword && accountPassword === userPassword.value) {
            sessionStorage.setItem("rentalUser", contact);
            userMessage.className = "login-message success";
            userMessage.textContent = "Berhasil masuk. Membuka katalog motor...";
            window.setTimeout(function () {
                window.location.href = "buku/list.html";
            }, 450);
            return;
        }

        userMessage.className = "login-message error";
        userMessage.textContent = accountPassword ? "Password salah. Silakan coba lagi atau gunakan Lupa password." : "Email belum terdaftar. Periksa kembali email atau daftar terlebih dahulu.";
    });

    const forgotModal = document.querySelector("#forgot-modal");
    const forgotForm = document.querySelector("#forgot-form");
    const forgotEmail = document.querySelector("#forgot-email");
    const forgotMessage = document.querySelector("#forgot-message");
    const newPassword = document.querySelector("#new-password");
    const newPasswordWrap = document.querySelector(".new-password-wrap");
    const newPasswordLabel = document.querySelector(".new-password-label");
    const forgotSubmit = document.querySelector("#forgot-submit");

    document.querySelector("#forgot-password-link").addEventListener("click", function (event) {
        event.preventDefault();
        forgotModal.hidden = false;
        forgotEmail.focus();
    });

    document.querySelector("#forgot-modal-close").addEventListener("click", function () {
        forgotModal.hidden = true;
    });

    forgotModal.addEventListener("click", function (event) {
        if (event.target === forgotModal) {
            forgotModal.hidden = true;
        }
    });

    document.querySelector(".reset-password-toggle").addEventListener("click", function (event) {
        const button = event.currentTarget;
        const isPassword = newPassword.type === "password";
        newPassword.type = isPassword ? "text" : "password";
        button.innerHTML = isPassword ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
    });

    forgotForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const email = forgotEmail.value.trim().toLowerCase();

        if (!registeredAccounts[email]) {
            forgotMessage.className = "login-message error";
            forgotMessage.textContent = "Email belum terdaftar di sistem.";
            return;
        }

        if (newPasswordWrap.hidden) {
            newPasswordWrap.hidden = false;
            newPasswordLabel.hidden = false;
            forgotSubmit.innerHTML = 'Simpan password baru <i class="bi bi-check2"></i>';
            forgotMessage.className = "login-message success";
            forgotMessage.textContent = "Email terdaftar. Silakan buat password baru.";
            newPassword.focus();
            return;
        }

        if (newPassword.value.length < 6) {
            forgotMessage.className = "login-message error";
            forgotMessage.textContent = "Password baru minimal 6 karakter.";
            return;
        }

        registeredAccounts[email] = newPassword.value;
        saveAccounts();
        forgotMessage.className = "login-message success";
        forgotMessage.textContent = "Password berhasil diubah. Silakan masuk dengan password baru.";
        forgotSubmit.disabled = true;
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
    initLogin();
});