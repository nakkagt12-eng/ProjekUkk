<?php
session_start();
require_once __DIR__ . '/../config/config.php';

/* ================= PROTEKSI USER ================= */
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}

/* Ambil semua type buku */
$typeQuery = $conn->query("SELECT * FROM type_buku");

/* Fungsi ambil 6 buku per type */
function getBukuByTypeId($conn, $id_type) {
    return $conn->query("
        SELECT * FROM buku 
        WHERE id_type = '$id_type'
        LIMIT 6
    ");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>SCLibrary | Buku</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../assets/css/navbaruser.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:'Poppins', Arial, sans-serif;
    background:#f6f7fb;
}

/* ===== NAVBAR ===== */

/* ===== CONTENT ===== */
.container-content{
    padding:40px;
}

.section{
    margin-bottom:50px;
}

.section-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.section-header h3{
    margin:0;
    font-size:20px;
    font-weight:600;
}

.section-header a{
    background:#e5e7eb;
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    text-decoration:none;
    color:#000;
}

/* ===== GRID 7 KOLOM ===== */
.grid{
    display:grid;
    grid-template-columns:repeat(7,1fr);
    gap:18px;
}

/* ===== CARD BUKU ===== */
.card-buku{
    border:1px solid #ddd;
    border-radius:12px;
    background:#fff;
    padding:10px;
    transition:.2s;
}

.card-buku:hover{
    transform:translateY(-4px);
    box-shadow:0 8px 20px rgba(0,0,0,.08);
}

.card-buku a{
    display:flex;
    flex-direction:column;
    align-items:center;
    text-decoration:none;
    color:#000;
}

/* GAMBAR BUKU */
.card-buku img{
    width:100%;
    max-width:120px;
    height:auto;
    border-radius:8px;
    object-fit:cover;
    margin-bottom:8px;
}

/* NAMA BUKU */
.card-buku .judul{
    font-size:13px;
    font-weight:500;
    text-align:center;
    line-height:1.3;
}

/* ===== RESPONSIVE ===== */
@media (max-width:1200px){
    .grid{
        grid-template-columns:repeat(5,1fr);
    }
}

@media (max-width:992px){
    .container-content{
        padding:30px 20px;
    }

    .grid{
        grid-template-columns:repeat(4,1fr);
    }
}

@media (max-width:768px){
    .grid{
        grid-template-columns:repeat(3,1fr);
    }
}

@media (max-width:576px){
    .container-content{
        padding:20px 14px;
    }

    .grid{
        grid-template-columns:repeat(2,1fr);
    }

    .card-buku img{
        max-width:100px;
    }

    .card-buku .judul{
        font-size:12px;
    }

    .section-header{
        flex-direction:column;
        align-items:flex-start;
        gap:6px;
    }
}

/* ===== FOOTER ===== */
footer{
    background:#0b3d91;
    color:#fff;
    padding:40px 0 16px;
}

.footer-brand{
    font-size:18px;
    font-weight:600;
}

.footer-desc{
    font-size:14px;
    opacity:.85;
    max-width:320px;
}

.footer-contact{
    text-align:right;
}

.footer-divider{
    margin-top:24px;
    border-top:1px solid rgba(255,255,255,.2);
}

.footer-bottom{
    text-align:center;
    font-size:13px;
    opacity:.8;
    margin-top:10px;
}
</style>
</head>

<body>

 
<?php include __DIR__ . '/navbar.php'; ?>


 
<div class="container-content">

<?php while($type = $typeQuery->fetch_assoc()): ?>
<?php
    $buku = getBukuByTypeId($conn, $type['id_type']);
    if ($buku->num_rows == 0) continue;
?>
<div class="section">
    <div class="section-header">
        <h3><?= htmlspecialchars($type['nama_type']) ?></h3>
        <a href="selengkapnya.php?type=<?= $type['id_type'] ?>">Selengkapnya</a>
    </div>

    <div class="grid">
        <?php while($b = $buku->fetch_assoc()): ?>
        <div class="card-buku">
            <a href="viewall.php?id=<?= $b['id_buku'] ?>">
                <img src="../assets/img/buku/<?= $b['img'] ?>" alt="<?= htmlspecialchars($b['nama_buku']) ?>">
                <div class="judul"><?= htmlspecialchars($b['nama_buku']) ?></div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php endwhile; ?>

</div>

 
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="footer-brand">SCLibrary</div>
                <div class="footer-desc">
                    Perpustakaan digital modern untuk mendukung pembelajaran yang efisien.
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
