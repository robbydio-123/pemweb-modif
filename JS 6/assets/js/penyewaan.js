const DENDA_PER_HARI = 25000;

function hitungLamaSewa(tanggalMulai, tanggalSelesai) {
    const mulai = new Date(`${tanggalMulai}T00:00:00`);
    const selesai = new Date(`${tanggalSelesai}T00:00:00`);
    return Math.max(1, Math.ceil((selesai - mulai) / 86400000));
}

function formatRupiah(nilai) {
    return `Rp${Number(nilai).toLocaleString("id-ID")}`;
}

function tampilkanPenyewaan(tbody, data) {
    tbody.innerHTML = data.map((sewa) => {
        const lama = hitungLamaSewa(sewa.tanggal_sewa, sewa.tanggal_kembali);
        const denda = Number(sewa.denda || 0);
        return `
            <tr>
                <td>${sewa.id}</td>
                <td>${sewa.nama_pelanggan}</td>
                <td>${sewa.plat_nomor} - ${sewa.model_motor}</td>
                <td>${sewa.tanggal_sewa}</td>
                <td>${lama} hari</td>
                <td>${formatRupiah(denda)}</td>
                <td><span class="status-${sewa.status.toLowerCase()}">${sewa.status}</span></td>
            </tr>
        `;
    }).join("");
}

async function muatPenyewaan() {
    const tbody = document.querySelector("#tabel-penyewaan");
    if (!tbody) return;

    try {
        const response = await fetch("../data/penyewaan.json");
        if (!response.ok) throw new Error("Gagal mengambil data penyewaan.");
        tampilkanPenyewaan(tbody, await response.json());
    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="7">Data penyewaan belum dapat dimuat.</td></tr>`;
        console.warn(error);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    muatPenyewaan();

    const form = document.querySelector("#form-penyewaan");
    const mulai = document.querySelector("#tanggal_sewa");
    const selesai = document.querySelector("#tanggal_kembali");
    const lama = document.querySelector("#lama_sewa");
    const total = document.querySelector("#total_biaya");
    const harga = document.querySelector("#harga_sewa_per_hari");
    const denda = document.querySelector("#denda");

    function perbaruiRingkasan() {
        if (!mulai || !selesai) return;
        if (!mulai.value || !selesai.value) {
            if (lama) lama.value = "-";
            if (total) total.value = "Rp0";
            if (denda) denda.value = "Rp0";
            return;
        }
        const jumlahHari = hitungLamaSewa(mulai.value, selesai.value);
        const nilaiDenda = mulai.value && selesai.value && new Date(selesai.value) < new Date() ? DENDA_PER_HARI : 0;
        if (lama) lama.value = `${jumlahHari} hari`;
        if (total) total.value = formatRupiah(jumlahHari * Number(harga?.value || 0));
        if (denda) denda.value = formatRupiah(nilaiDenda);
    }

    [mulai, selesai, harga].forEach((input) => input?.addEventListener("input", perbaruiRingkasan));
    form?.addEventListener("submit", (event) => {
        event.preventDefault();
        alert("Data penyewaan berhasil disiapkan. Hubungkan form ini ke endpoint database saat backend tersedia.");
    });
    perbaruiRingkasan();
});