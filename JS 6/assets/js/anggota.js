const dataAnggotaCadangan = [
    { no_pelanggan: "P001", nama: "Siti Aminah", no_ktp: "3573012345670001", alamat: "Lowokwaru, Malang", no_hp: "081234567890" },
    { no_pelanggan: "P002", nama: "Budi Santoso", no_ktp: "3573012345670002", alamat: "Tlogomas, Malang", no_hp: "081345678901" },
    { no_pelanggan: "P003", nama: "Citra Lestari", no_ktp: "3573012345670003", alamat: "Dinoyo, Malang", no_hp: "081456789012" },
    { no_pelanggan: "P004", nama: "Dimas Pratama", no_ktp: "3573012345670004", alamat: "Merjosari, Malang", no_hp: "081567890123" }
];

function tampilkanAnggota(tbody, dataAnggota) {
    tbody.innerHTML = "";

    dataAnggota.forEach(function (anggota) {
        const row = document.createElement("tr");

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

        tbody.appendChild(row);
    });
}

async function muatDaftarAnggota() {
    const tbody = document.querySelector("#tabel-anggota");
    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="6">Memuat data...</td>
        </tr>
    `;

    try {
        const response = await fetch("../data/anggota.json");

        if (!response.ok) {
            throw new Error("Gagal mengambil data pelanggan.");
        }

        const dataAnggota = await response.json();
        tampilkanAnggota(tbody, dataAnggota);
    } catch (error) {
        console.warn("JSON pelanggan tidak dapat dimuat, memakai data cadangan.", error);
        tampilkanAnggota(tbody, dataAnggotaCadangan);
    }
}
document.addEventListener("DOMContentLoaded", muatDaftarAnggota);