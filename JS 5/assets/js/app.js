function initNavToggle() {
    const navToggle = document.querySelector("#nav-toggle");
    const nav = document.querySelector("header nav");

    if (!navToggle || !nav) return;

    navToggle.addEventListener("change", function () {
        nav.classList.toggle("show", navToggle.checked);
    });
}
function initHapusConfirm() {
    const tombolHapus = document.querySelectorAll(".btn-hapus");

    tombolHapus.forEach(function (tombol) {
        tombol.addEventListener("click", function () {
            const baris = tombol.closest("tr");

            if (confirm("Apakah kamu yakin ingin menghapus data ini?")) {
                if (baris) {
                    baris.remove();
                }
            }
        });
    });
}

function initTableFilter() {
    const searchBox = document.querySelector("#search-box");
    const rows = document.querySelectorAll("table tbody tr");

    if (!searchBox || rows.length === 0) return;

    searchBox.addEventListener("keyup", function () {
        const keyword = searchBox.value.toLowerCase();

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
        const noAnggota = form.querySelector("#no_anggota");
        const judul = form.querySelector("#judul");
        const pengarang = form.querySelector("#pengarang");
        const tahun = form.querySelector("#tahun");
        const stok = form.querySelector("#stok");

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
            event.preventDefault();
        }
    });
}
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});