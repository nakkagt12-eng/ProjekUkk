<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];
$aksi = $_GET['aksi'];

/* Ambil data pinjaman */
$data = $conn->query("
    SELECT id_buku, status 
    FROM pinjam 
    WHERE id_pinjam = '$id'
")->fetch_assoc();

$id_buku = $data['id_buku'];
$status_lama = $data['status'];

if ($aksi == 'pinjam' && $status_lama == 'diproses') {

    // ubah status
    $conn->query("UPDATE pinjam SET status='dipinjam' WHERE id_pinjam='$id'");

    // kurangi stok
    $conn->query("UPDATE buku SET stok = stok - 1 WHERE id_buku='$id_buku'");

} elseif ($aksi == 'kembali' && $status_lama == 'dipinjam') {

    // ubah status
    $conn->query("UPDATE pinjam SET status='dikembalikan' WHERE id_pinjam='$id'");

    // tambah stok
    $conn->query("UPDATE buku SET stok = stok + 1 WHERE id_buku='$id_buku'");
}

header("Location: pinjaman.php");
exit;
