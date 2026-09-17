const dataBukuLokal = [
    { plat_nomor: "N 1234 AB", merek: "Honda", model: "Vario 160", tahun: 2024, warna: "Hitam", jenis: "Matic", kapasitas_cc: 160, harga_sewa_per_hari: 85000, status: "Tersedia" },
    { plat_nomor: "N 2345 CD", merek: "Yamaha", model: "NMAX", tahun: 2023, warna: "Biru", jenis: "Matic", kapasitas_cc: 155, harga_sewa_per_hari: 120000, status: "Tersedia" },
    { plat_nomor: "N 3456 EF", merek: "Honda", model: "Beat Street", tahun: 2022, warna: "Merah", jenis: "Matic", kapasitas_cc: 110, harga_sewa_per_hari: 70000, status: "Disewa" }
];

function tampilkanBuku(tbody, dataBuku) {
    tbody.innerHTML = "";

    dataBuku.forEach(function (buku) {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${buku.plat_nomor}</td>
            <td>${buku.merek} ${buku.model}</td>
            <td>${buku.tahun}</td>
            <td>${buku.jenis}</td>
            <td>Rp${Number(buku.harga_sewa_per_hari).toLocaleString("id-ID")}</td>
            <td><span class="status-${buku.status.toLowerCase()}">${buku.status}</span></td>
            <td>
                <button type="button">Edit</button>
                <button type="button" class="btn-hapus">Hapus</button>
            </td>
        `;

        tbody.appendChild(row);
    });
}

async function muatDaftarBuku() {
    const tbody = document.querySelector("#tabel-buku");

    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="7">Memuat data...</td>
        </tr>
    `;

    try {
        const response = await fetch("../data/buku.json");

        if (!response.ok) {
            throw new Error("Gagal mengambil data motor.");
        }

        const dataBuku = await response.json();
        tampilkanBuku(tbody, dataBuku);
    } catch (error) {
        console.warn("JSON motor tidak dapat dimuat, memakai data lokal.", error);
        tampilkanBuku(tbody, dataBukuLokal);
    }
}

document.addEventListener("DOMContentLoaded", muatDaftarBuku);