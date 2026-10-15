// Besaran denda contoh yang dikenakan untuk keterlambatan.
const DENDA_PER_HARI = 25000;

// Menghitung lama sewa berdasarkan tanggal mulai dan tanggal kembali.
function hitungLamaSewa(tanggalMulai, tanggalSelesai) {
  // Tambahkan waktu tengah malam agar perhitungan memakai awal hari.
  const mulai = new Date(`${tanggalMulai}T00:00:00`);
  const selesai = new Date(`${tanggalSelesai}T00:00:00`);
  // Ubah selisih milidetik menjadi hari dan pastikan minimal satu hari.
  return Math.max(1, Math.ceil((selesai - mulai) / 86400000));
}

// Mengubah angka menjadi format Rupiah Indonesia.
function formatRupiah(nilai) {
  return `Rp${Number(nilai).toLocaleString("id-ID")}`;
}

function tampilkanPenyewaan(tbody, data) {
  // Ubah setiap data penyewaan menjadi baris, lalu gabungkan hasilnya.
  tbody.innerHTML = data
    .map((sewa) => {
      // Hitung durasi dan ubah denda menjadi angka untuk ditampilkan.
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
    })
    .join("");
}

async function muatPenyewaan() {
  const tbody = document.querySelector("#tabel-penyewaan");
  // Tidak ada yang perlu dimuat bila tabel tidak berada di halaman ini.
  if (!tbody) return;

  try {
    // Ambil data transaksi dari berkas JSON.
    const response = await fetch("../data/penyewaan.json");
    // Perlakukan respons HTTP gagal sebagai error.
    if (!response.ok) throw new Error("Gagal mengambil data penyewaan.");
    // Tampilkan isi JSON setelah berhasil dibaca.
    tampilkanPenyewaan(tbody, await response.json());
  } catch (error) {
    // Tampilkan pesan di tabel dan catat error untuk pemeriksaan.
    tbody.innerHTML = `<tr><td colspan="7">Data penyewaan belum dapat dimuat.</td></tr>`;
    console.warn(error);
  }
}

// Menyiapkan tabel dan formulir setelah HTML selesai dimuat.
document.addEventListener("DOMContentLoaded", function () {
  // Mulai memuat riwayat penyewaan jika tabel tersedia.
  muatPenyewaan();

  // Baca parameter URL untuk mengisi motor yang dipilih dari katalog.
  const parameter = new URLSearchParams(window.location.search);
  const platNomor = document.querySelector("#plat_nomor");
  const hargaMotor = document.querySelector("#harga_sewa_per_hari");
  // Isi plat dan harga hanya jika input serta parameternya tersedia.
  if (platNomor && parameter.has("plat_nomor"))
    platNomor.value = parameter.get("plat_nomor");
  if (hargaMotor && parameter.has("harga"))
    hargaMotor.value = parameter.get("harga");

  const form = document.querySelector("#form-penyewaan");
  const mulai = document.querySelector("#tanggal_sewa");
  const selesai = document.querySelector("#tanggal_kembali");
  const lama = document.querySelector("#lama_sewa");
  const total = document.querySelector("#total_biaya");
  const harga = document.querySelector("#harga_sewa_per_hari");
  const denda = document.querySelector("#denda");

  // Hitung ulang ringkasan sewa setiap kali tanggal atau harga berubah.
  function perbaruiRingkasan() {
    // Ringkasan hanya dapat dihitung jika kedua input tanggal tersedia.
    if (!mulai || !selesai) return;
    // Gunakan nilai awal saat salah satu tanggal belum dipilih.
    if (!mulai.value || !selesai.value) {
      if (lama) lama.value = "-";
      if (total) total.value = "Rp0";
      if (denda) denda.value = "Rp0";
      return;
    }
    // Hitung hari sewa dan denda contoh jika tanggal kembali sudah lewat.
    const jumlahHari = hitungLamaSewa(mulai.value, selesai.value);
    const nilaiDenda =
      mulai.value && selesai.value && new Date(selesai.value) < new Date()
        ? DENDA_PER_HARI
        : 0;
    if (lama) lama.value = `${jumlahHari} hari`;
    if (total)
      total.value = formatRupiah(jumlahHari * Number(harga?.value || 0));
    if (denda) denda.value = formatRupiah(nilaiDenda);
  }

  // Perubahan tanggal/harga memicu perhitungan ulang langsung.
  [mulai, selesai, harga].forEach((input) =>
    input?.addEventListener("input", perbaruiRingkasan),
  );
  // Formulir demo belum mengirim transaksi ke backend/database.
  form?.addEventListener("submit", (event) => {
    event.preventDefault();
    alert(
      "Data penyewaan berhasil disiapkan. Hubungkan form ini ke endpoint database saat backend tersedia.",
    );
  });
  // Tampilkan ringkasan awal jika nilai tanggal sudah tersedia.
  perbaruiRingkasan();
});
