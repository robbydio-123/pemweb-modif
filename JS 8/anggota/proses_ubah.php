<?php
session_start(); require __DIR__ . '/../includes/auth.php'; require_admin(); require __DIR__ . '/../includes/koneksi.php';
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); $nama = trim($_POST['nama'] ?? ''); $nomor = trim($_POST['no_pelanggan'] ?? ''); $alamat = trim($_POST['alamat'] ?? ''); $hp = trim($_POST['no_hp'] ?? '');
if (!$id || $nama === '' || $nomor === '' || $hp === '') { $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Data pelanggan tidak valid.']; header('Location: list.php'); exit; }
try { $stmt = $pdo->prepare('UPDATE pelanggan SET nama=:nama, no_pelanggan=:nomor, alamat=:alamat, no_hp=:hp WHERE id=:id'); $stmt->execute(['id' => $id, 'nama' => $nama, 'nomor' => $nomor, 'alamat' => $alamat, 'hp' => $hp]); $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Pelanggan berhasil diubah.']; } catch (PDOException $e) { $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Nomor pelanggan sudah digunakan.']; }
header('Location: list.php'); exit;