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
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});