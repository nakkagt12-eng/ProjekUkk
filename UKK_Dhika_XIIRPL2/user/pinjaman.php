<?php
session_start();
require_once __DIR__ . '/../config/config.php';

/* ================= PROTEKSI USER ================= */
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}

/* ================= VALIDASI PARAM ================= */
if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit;
}

$id_buku  = intval($_GET['id']);
$username = $_SESSION['username'];

/* ================= DATA USER ================= */
$user = $conn->query("
    SELECT email FROM users 
    WHERE username = '$username'
")->fetch_assoc();

/* ================= DATA BUKU ================= */
$buku = $conn->query("
    SELECT buku.*, type_buku.nama_type,
           GROUP_CONCAT(genre.nama_genre SEPARATOR ', ') AS genre
    FROM buku
    LEFT JOIN type_buku ON buku.id_type = type_buku.id_type
    LEFT JOIN buku_genre ON buku.id_buku = buku_genre.id_buku
    LEFT JOIN genre ON buku_genre.id_genre = genre.id_genre
    WHERE buku.id_buku = '$id_buku'
    GROUP BY buku.id_buku
")->fetch_assoc();

/* ================= PROSES PINJAM ================= */
if (isset($_POST['pinjam'])) {
    $tgl_pinjam  = $_POST['tgl_pinjam'];
    $tgl_kembali = $_POST['tgl_kembali'];

    $conn->query("
        INSERT INTO pinjam (id_buku, username, tgl_pinjam, tgl_kembali, status)
        VALUES ('$id_buku', '$username', '$tgl_pinjam', '$tgl_kembali', 'diproses')
    ");

    header("Location: history.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pinjam Buku | SCLibrary</title>

<link href="../assets/css/navbaruser.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    background:#f6f7fb;
    font-family:'Poppins', sans-serif;
}
.form-box{
    background:#fff;
    padding:30px;
    border-radius:18px;
}
.form-control{
    background:#e5e7eb;
    border:none;
    border-radius:10px;
}
.cover{
    width:220px;
    height:300px;
    border:1px solid #ddd;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
}
.cover img{
    width:140px;
    height:200px;
    object-fit:cover;
}
.info{
    font-size:14px;
}
.pinjam-btn{
    border-radius:20px;
    padding:6px 24px;
}
footer{
    background:#0b2c7c;
    color:#fff;
    padding:40px;
    margin-top:60px;
}
</style>
</head>

<body>

<?php include __DIR__ . '/navbar.php'; ?>

<div class="container mt-5">
    <div class="row align-items-start">

        <!-- FORM PINJAM -->
        <div class="col-md-6">
            <div class="form-box">
                <form method="post">

                    <label class="mb-1">Nama</label>
                    <input type="text" class="form-control mb-3"
                           value="<?= htmlspecialchars($username) ?>" readonly>

                    <label class="mb-1">Email</label>
                    <input type="email" class="form-control mb-3"
                           value="<?= htmlspecialchars($user['email']) ?>" readonly>

                    <label class="mb-1">Buku</label>
                    <input type="text" class="form-control mb-3"
                           value="<?= htmlspecialchars($buku['nama_buku']) ?>" readonly>

                    <!-- TANGGAL PINJAM -->
                    <label class="mb-1">Tgl Peminjaman</label>
                    <input type="date" name="tgl_pinjam"
                           class="form-control mb-3"
                           value="<?= date('Y-m-d') ?>" readonly>

                    <!-- TANGGAL KEMBALI -->
                    <label class="mb-1">Tgl Pengembalian</label>
                    <input type="date" name="tgl_kembali"
                           class="form-control mb-4" required>

                    <button type="submit" name="pinjam"
                            class="btn btn-primary pinjam-btn">
                        Pinjam
                    </button>

                </form>
            </div>
        </div>

        <!-- INFO BUKU -->
        <div class="col-md-6 text-center">
            <div class="cover mx-auto mb-3">
                <img src="../assets/img/buku/<?= htmlspecialchars($buku['img']) ?>">
            </div>

            <div class="info">
                <p><strong>Nama:</strong> <?= htmlspecialchars($buku['nama_buku']) ?></p>
                <p><strong>Genre:</strong> <?= htmlspecialchars($buku['genre']) ?></p>
                <p><strong>Kategori:</strong> <?= htmlspecialchars($buku['nama_type']) ?></p>
            </div>
        </div>

    </div>
</div>

<footer>
    <div class="container d-flex justify-content-between">
        <div>
            <h5>SCLibrary</h5>
            <p>Perpustakaan Digital</p>
        </div>
        <div>
            <p>📍 SMK Wira Harapan</p>
            <p>📞 0817-7923-3779</p>
            <p>✉ smkwiraharapan@gmail.com</p>
        </div>
    </div>
</footer>

</body>
</html>
