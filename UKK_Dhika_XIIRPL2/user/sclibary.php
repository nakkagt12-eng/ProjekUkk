<?php
session_start();

/* ================= PROTEKSI USER ================= */
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tentang SCLibrary | SCLibrary</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../assets/css/navbaruser.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Peralta&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
}


.header {
    padding: 100px 0 60px;
    background: #f8f9fa;
    text-align: center;
}

.header h1 {
    font-weight: 700;
    color: #003099;
}

.header span {
    color: #FF6E0F;
    font-family: 'Peralta', serif;
}

.content {
    padding: 80px 0;
}

.content p {
    line-height: 1.9;
    color: #555;
}

 .highlight {
    background: #eef3ff;
    border-left: 6px solid #003099;
    padding: 24px;
    border-radius: 10px;
}

 footer {
    background: #0b3d91;
    color: #ffffff;
    padding: 40px 0 16px;
}

.footer-divider {
    margin-top: 24px;
    border-top: 1px solid rgba(255,255,255,.2);
}

.footer-bottom {
    text-align: center;
    font-size: 13px;
    opacity: 0.8;
    margin-top: 10px;
}
</style>
</head>

<body>

 <?php include __DIR__ . '/navbar.php'; ?>

 <section class="header">
    <div class="container">
        <h1><span>SC</span>Library</h1>
        <p class="text-muted mt-3">
            Solusi perpustakaan digital modern untuk sekolah
        </p>
    </div>
</section>

 <section class="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <p>
                    <strong>SCLibrary by Nakka</strong> adalah layanan perpustakaan digital berbasis web
                    yang dirancang untuk memenuhi kebutuhan perpustakaan sekolah secara modern,
                    efisien, dan terintegrasi. Aplikasi ini dikembangkan sebagai solusi atas
                    pengelolaan perpustakaan manual yang kurang efektif.
                </p>

                <p>
                    SCLibrary memungkinkan pengelolaan data buku secara lengkap, mulai dari
                    pencatatan koleksi, pengelompokan jenis dan genre buku, hingga pengelolaan
                    stok secara real-time. Setiap buku dilengkapi dengan detail informasi
                    seperti judul, penulis, kategori, deskripsi, dan cover.
                </p>

                <div class="highlight my-4">
                    <strong>Fitur utama SCLibrary:</strong>
                    <ul class="mt-2 mb-0">
                        <li>Manajemen data buku terstruktur</li>
                        <li>Sistem peminjaman dan pengembalian otomatis</li>
                        <li>Pencarian dan filter buku yang cepat</li>
                        <li>Mudah digunakan dan intuitif</li>
                        <li>Tampilan modern dan responsif</li>
                    </ul>
                </div>

                <p>
                    Dengan antarmuka yang ramah pengguna dan sistem yang aman,
                    SCLibrary membantu meningkatkan efisiensi administrasi
                    perpustakaan serta mendukung proses belajar mengajar
                    di lingkungan sekolah.
                </p>

                <div class="text-center mt-5">
                    <a href="list.php" class="btn btn-primary px-4 rounded-pill">
                        Mulai Pinjam Buku
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <strong>SCLibrary</strong>
                <p class="small mt-2">
                    Perpustakaan digital modern untuk mendukung pembelajaran.
                </p>
            </div>
            <div class="col-md-6 text-end">
                <p class="small mb-1">📍 SMK Wira Harapan</p>
                <p class="small mb-1">📞 0817-7023-3379</p>
                <p class="small">✉ smkwiraharapan@gmail.com</p>
            </div>
        </div>

        <div class="footer-divider"></div>

        <div class="footer-bottom">
            © <?= date('Y') ?> SCLibrary. All rights reserved.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
