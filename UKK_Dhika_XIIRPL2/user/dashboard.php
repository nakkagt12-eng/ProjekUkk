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
<title>Dashboard User | SCLibrary</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Peralta&display=swap" rel="stylesheet">
<link href="../assets/css/navbaruser.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
b body {
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

         

         .hero {
            padding: 90px 0;
        }

         .hero-sc {
            font-family: 'Peralta', serif;
            font-size: 100px;
            color: #FF6E0F;
            line-height: 1.1;
        }

        .hero-library {
            font-size: 56px;
            font-weight: 700;
            color: #003099;
        }

        .hero h4 {
            margin-top: 10px;
            font-weight: 500;
        }

        .hero p {
            margin: 20px 0;
            max-width: 480px;
            color: #666;
        }

        .hero-logo {
            width: 500px;
            height: auto;
        }

        @media (max-width: 768px) {
            .hero {
                text-align: center;
                padding: 60px 0;
            }

            .hero-sc {
                font-size: 46px;
            }

            .hero-library {
                font-size: 38px;
            }

            .hero-logo {
                width: 220px;
                margin-top: 30px;
            }
        }

         .about {
            padding: 100px 0;
            background: #f8f9fa;
        }

        .about h3 {
            font-size: 32px;
        }

        .about p {
            font-size: 15px;
            line-height: 1.8;
        }

        .about-video {
            background: #e5e7eb;
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }

        .about-video video {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

         
        .feature {
            padding: 80px 0;
            text-align: center;
        }

        .feature-card {
            width: 220px;
            margin: 40px auto 0;
            padding: 32px 0;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
        }

        
        footer {
            background: #0b3d91;
            color: #ffffff;
            padding: 40px 0 16px;
        }

        .footer-brand {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .footer-desc {
            font-size: 14px;
            opacity: 0.85;
            max-width: 320px;
        }

        .footer-contact {
            text-align: right;
        }

        .footer-divider {
            margin-top: 24px;
            border-top: 1px solid rgba(41, 38, 120, 0.25);
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

<!-- ================= NAVBAR ================= -->
<?php include __DIR__ . '/navbar.php'; ?>


<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>
                    <span class="hero-sc">SC</span>
                    <span class="hero-library">Library</span>
                </h1>

                <h4>Perpustakaan Modern</h4>

                <p>
                    SCLibrary by Nakka adalah layanan perpustakaan digital
                    yang dirancang untuk memenuhi kebutuhan
                    perpustakaan sekolah secara modern.
                </p>

                <a href="sclibary.php" class="btn btn-primary px-4">Selengkapnya</a>
            </div>

            <div class="col-md-6 text-center">
                <img src="../assets/img/hero-logo.png" class="hero-logo">
            </div>
        </div>
    </div>
</section>

<!-- ABOUT -->
<section class="about" id="about">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-md-6">
                <div class="about-video">
                    <video controls>
                        <source src="assets/video/profile.mp4" type="video/mp4">
                    </video>
                </div>
            </div>

            <div class="col-md-6">
                <h3 class="fw-bold mb-3">Kenali Kami</h3>
                <p class="text-muted">
                    SCLibrary adalah platform perpustakaan digital
                    yang dikembangkan oleh <strong>Nakka</strong>
                    untuk membantu sekolah mengelola data buku,
                    peminjaman, dan administrasi perpustakaan.
                </p>
                <p class="text-muted">
                    Dengan sistem yang fleksibel dan tampilan modern,
                    SCLibrary mendukung proses pembelajaran
                    yang lebih efektif dan terstruktur.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FEATURE -->
<section class="feature" id="pinjam">
    <div class="container">
        <h3 class="fw-bold">Kelola Perpustakaan dengan Praktis</h3>
        <p class="text-muted">Sistem peminjaman buku yang cepat dan efisien</p>

        <div class="feature-card">
            <div class="display-5 mb-3">📘</div>
            <a href="list.php" class="btn btn-primary btn-sm rounded-pill">Pinjam</a>
        </div>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="footer-brand">SCLibrary</div>
                <div class="footer-desc">
                    Perpustakaan digital modern untuk mendukung
                    pembelajaran yang efisien.
                </div>
            </div>

            <div class="col-md-6 footer-contact">
                <p>📍 SMK Wira Harapan</p>
                <p>📞 0817-7023-3379</p>
                <p>✉ smkwiraharapan@gmail.com</p>
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
