<?php
session_start();
require_once __DIR__ . '/../config/config.php';

/* PROTEKSI USER */
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}

$id_buku     = $_POST['id_buku'] ?? null;
$username    = $_SESSION['username'];
$tgl_pinjam  = $_POST['tgl_pinjam'];
$tgl_kembali = $_POST['tgl_kembali'];

if (!$id_buku) {
    header("Location: list.php");
    exit;
}

/* SIMPAN PINJAMAN (TANPA KURANGI STOK) */
$conn->query("
    INSERT INTO pinjam (id_buku, username, tgl_pinjam, tgl_kembali, status)
    VALUES ('$id_buku', '$username', '$tgl_pinjam', '$tgl_kembali', 'diproses')
");

header("Location: history.php");
exit;
