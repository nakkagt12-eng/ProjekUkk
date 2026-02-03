<?php
session_start();
$conn = new mysqli("localhost","root","","sclibary");

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id_pinjam = $_GET['id'];

/* Ambil data pinjaman */
$q = $conn->query("
    SELECT p.id_buku, b.stok 
    FROM pinjam p 
    JOIN buku b ON p.id_buku = b.id_buku
    WHERE p.id_pinjam = '$id_pinjam'
");
$data = $q->fetch_assoc();

if (!$data || $data['stok'] <= 0) {
    echo "<script>alert('Stok habis');history.back();</script>";
    exit;
}

/* Update status */
$conn->query("
    UPDATE pinjam 
    SET status='dipinjam' 
    WHERE id_pinjam='$id_pinjam'
");

/* Kurangi stok */
$conn->query("
    UPDATE buku 
    SET stok = stok - 1 
    WHERE id_buku='{$data['id_buku']}'
");

header("Location: pinjaman.php");
exit;
