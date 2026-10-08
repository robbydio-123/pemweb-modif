    </main>

    <footer class="site-footer">
        <div class="footer-grid">
            <div class="footer-col footer-about">
                <div class="footer-brand">
                    <span class="footer-logo">🛵</span>
                    <div>
                        <strong>RENTAL MOTOR LOWOKWARU</strong>
                        <p class="tagline">Solusi Transportasi Terbaik di Malang Raya</p>
                    </div>
                </div>
                <p class="about-text">
                    Menyediakan armada motor matic & manual keluaran terbaru, bersih, dan prima.
                    Lokasi strategis di kawasan Lowokwaru dekat Universitas Brawijaya (UB), Polinema, UM, dan UMM.
                </p>
                <div class="contact-pill">
                    <span>📱 WA: <strong>0812-3456-7890</strong> (Fast Respon 24 Jam)</span>
                </div>
            </div>

            <div class="footer-col">
                <h4>Fasilitas Sewa</h4>
                <ul class="footer-links">
                    <li>🛵 Gratis 2 Helm SNI Bersih & Wangi</li>
                    <li>🌧️ Gratis 2 Jas Hujan Berkualitas</li>
                    <li>📍 Antar Jemput Stasiun & Kampus Lowokwaru</li>
                    <li>🔧 Unit Dicek & Diservis Rutin Setiap Balik</li>
                    <li>🔒 Kunci Ganda & Holder HP Ready</li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Navigasi Cepat</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo $base; ?>index.php">Beranda</a></li>
                    <li><a href="<?php echo $base; ?>buku/list.php">Katalog Armada Motor</a></li>
                    <?php if ($sudahLogin): ?>
                        <li><a href="<?php echo $base; ?>peminjaman/tambah.php">Input Sewa Baru</a></li>
                        <li><a href="<?php echo $base; ?>peminjaman/kembali.php">Pengembalian Unit</a></li>
                        <li><a href="<?php echo $base; ?>peminjaman/riwayat.php">Riwayat Transaksi</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo $base; ?>auth/login.php">Portal Petugas</a></li>
                        <li><a href="<?php echo $base; ?>auth/register.php">Daftar Akun Petugas</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Lokasi & Jam Kerja</h4>
                <p>📍 Jl. MT Haryono No. 12, Kel. Dinoyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65144</p>
                <p class="mt-2">⏰ <strong>Buka Setiap Hari:</strong> 06.00 - 22.00 WIB</p>
                <p>🚀 Antar jemput subuh / malam bisa konfirmasi via WhatsApp.</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <strong>Rental Motor Lowokwaru Malang</strong>. Seluruh Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script src="<?php echo $base; ?>assets/js/app.js"></script>
</body>
</html>