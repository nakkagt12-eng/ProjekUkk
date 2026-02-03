<?php
session_start();
require_once __DIR__ . '/../config/config.php';

 if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}

 if (!isset($_GET['type'])) {
    header("Location: list.php");
    exit;
}

$id_type = intval($_GET['type']);

 $typeQuery = $conn->query("SELECT * FROM type_buku WHERE id_type = '$id_type'");
$type = $typeQuery->fetch_assoc();

 $bukuQuery = $conn->query("SELECT * FROM buku WHERE id_type = '$id_type'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($type['nama_type']) ?> | SCLibrary</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../assets/css/navbaruser.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    background:#f6f7fb;
    font-family:'Poppins', Arial, sans-serif;
}

 .container-content{
    padding:40px 20px;
}

 .grid{
    display:grid;
    grid-template-columns:repeat(7,1fr);
    gap:18px;
}

 .card-buku{
    background:#fff;
    border:1px solid #ddd;
    border-radius:12px;
    padding:10px;
    transition:.25s;
}

.card-buku:hover{
    transform:translateY(-4px);
    box-shadow:0 8px 20px rgba(0,0,0,.1);
}

.card-buku a{
    display:flex;
    flex-direction:column;
    align-items:center;
    text-decoration:none;
    color:#000;
}

 .card-buku img{
    width:100%;
    max-width:120px;
    height:auto;
    border-radius:8px;
    object-fit:cover;
    margin-bottom:8px;
}

  .card-buku .judul{
    font-size:13px;
    font-weight:500;
    text-align:center;
    line-height:1.3;
}

 .btn-back{
    border-radius:20px;
    font-size:14px;
}

 @media (max-width:1200px){
    .grid{ grid-template-columns:repeat(5,1fr); }
}

@media (max-width:992px){
    .grid{ grid-template-columns:repeat(4,1fr); }
}

@media (max-width:768px){
    .grid{ grid-template-columns:repeat(3,1fr); }
}

@media (max-width:576px){
    .container-content{ padding:20px 14px; }
    .grid{ grid-template-columns:repeat(2,1fr); }
    .card-buku img{ max-width:100px; }
    .card-buku .judul{ font-size:12px; }
}

 footer{
    background:#0b2c7c;
    color:#fff;
    padding:40px 0;
    margin-top:60px;
}
footer p{
    margin:4px 0;
    font-size:14px;
}
</style>
</head>

<body>

 <?php include __DIR__ . '/navbar.php'; ?>

 <div class="container-content container">

    <a href="list.php" class="btn btn-outline-primary btn-back mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <h4 class="mb-4"><?= htmlspecialchars($type['nama_type']) ?></h4>

    <div class="grid">
        <?php while($b = $bukuQuery->fetch_assoc()): ?>
            <div class="card-buku">
                <a href="viewall.php?id=<?= $b['id_buku'] ?>">
                    <img src="../assets/img/buku/<?= $b['img'] ?>" alt="<?= htmlspecialchars($b['nama_buku']) ?>">
                    <div class="judul"><?= htmlspecialchars($b['nama_buku']) ?></div>
                </a>
            </div>
        <?php endwhile; ?>
    </div>

</div>

 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
