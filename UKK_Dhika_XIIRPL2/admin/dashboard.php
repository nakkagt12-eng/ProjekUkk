<?php
session_start();
require_once "../config/config.php";

/* ================= PROTEKSI ADMIN ================= */
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

/* ================= SUMMARY ================= */
$totalBuku   = $conn->query("SELECT COUNT(*) FROM buku")->fetch_row()[0];
$totalGenre  = $conn->query("SELECT COUNT(*) FROM genre")->fetch_row()[0];
$totalType   = $conn->query("SELECT COUNT(*) FROM type_buku")->fetch_row()[0];
$totalUser   = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$totalPinjam = $conn->query("SELECT COUNT(*) FROM pinjam")->fetch_row()[0];

$pinjamAktif = $conn->query("
    SELECT COUNT(*) FROM pinjam 
    WHERE status IN ('diproses','dipinjam')
")->fetch_row()[0];

/* ================= GRAFIK ================= */
$qGrafik = $conn->query("
    SELECT MONTH(tgl_pinjam) bulan, COUNT(*) total
    FROM pinjam
    GROUP BY MONTH(tgl_pinjam)
    ORDER BY bulan
");

$bulan = [];
$total = [];

while ($g = $qGrafik->fetch_assoc()) {
    $bulan[] = date("M", mktime(0,0,0,$g['bulan'],1));
    $total[] = $g['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin | SCLibrary</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body{font-family:Poppins,sans-serif;background:#f5f6fa}
.dashboard-container{display:flex;min-height:100vh}
main{flex:1;padding:30px}
.card{border:none;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.06)}
</style>
</head>
<body>

<div class="dashboard-container">
<?php include "navbar.php"; ?>

<main>
<h3 class="mb-4 fw-semibold">Dashboard Admin</h3>

<div class="row g-4">
<?php
$cards = [
    ["Total Buku",$totalBuku,"book","primary"],
    ["Total Genre",$totalGenre,"tags","success"],
    ["Type Buku",$totalType,"collection","warning"],
    ["Total User",$totalUser,"people","info"],
    ["Total Pinjaman",$totalPinjam,"journal-text","secondary"],
    ["Pinjaman Aktif",$pinjamAktif,"clock-history","danger"]
];
foreach($cards as $c):
?>
<div class="col-md-4">
<div class="card">
<div class="card-body d-flex justify-content-between align-items-center">
<div>
<small class="text-muted"><?= $c[0] ?></small>
<h3><?= $c[1] ?></h3>
</div>
<i class="bi bi-<?= $c[2] ?> fs-1 text-<?= $c[3] ?>"></i>
</div>
</div>
</div>
<?php endforeach; ?>
</div>

<div class="card mt-4">
<div class="card-body">
<h5 class="mb-3">📊 Grafik Total Peminjaman</h5>
<canvas id="grafikPinjam" height="100"></canvas>
</div>
</div>

</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('grafikPinjam'),{
type:'line',
data:{
labels:<?= json_encode($bulan) ?>,
datasets:[{
label:'Total Peminjaman',
data:<?= json_encode($total) ?>,
borderWidth:3,
tension:.4,
fill:true
}]
},
options:{plugins:{legend:{display:false}}}
});
</script>

</body>
</html>
