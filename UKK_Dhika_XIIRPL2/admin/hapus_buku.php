<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

/* ambil gambar */
$q = $conn->query("SELECT img FROM buku WHERE id_buku='$id'");
$b = $q->fetch_assoc();

/* hapus relasi genre */
$conn->query("DELETE FROM buku_genre WHERE id_buku='$id'");

/* hapus buku */
$conn->query("DELETE FROM buku WHERE id_buku='$id'");

/* hapus file gambar */
if ($b && file_exists("../assets/img/buku/".$b['img'])) {
    unlink("../assets/img/buku/".$b['img']);
}

header("Location: buku.php");
    