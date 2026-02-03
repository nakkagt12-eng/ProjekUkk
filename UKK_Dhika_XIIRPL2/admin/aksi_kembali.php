<?php
session_start();
$conn = new mysqli("localhost","root","","sclibary");

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id_pinjam = $_GET['id'];

/* Ambil data */
$data = $conn->query("
    SELECT id_buku, status 
    FROM pinjam 
    WHERE id_pinjam='$id_pinjam'
")->fetch_assoc();

if (!$data || $data['status'] === 'dikembalikan') {
    header("Location: pinjaman.php");
    exit;
}

/* Update status + tanggal dikembalikan */
$conn->query("
    UPDATE pinjam 
    SET 
        status='dikembalikan',
        tgl_dikembalikan = CURDATE()
    WHERE id_pinjam='$id_pinjam'
");

/* Tambah stok */
$conn->query("
    UPDATE buku 
    SET stok = stok + 1 
    WHERE id_buku='{$data['id_buku']}'
");

header("Location: pinjaman.php");
exit;
