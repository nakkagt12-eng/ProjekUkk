<?php
session_start();
date_default_timezone_set('Asia/Makassar'); // WITA
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}

$username = $_SESSION['username'];

$query = $conn->query("
    SELECT 
        p.id_pinjam,
        b.nama_buku,
        p.tgl_pinjam,
        p.tgl_dikembalikan,
        p.status
    FROM pinjam p
    JOIN buku b ON p.id_buku = b.id_buku
    WHERE p.username = '$username'
    ORDER BY p.tgl_pinjam DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>History Peminjaman | SCLibrary</title>
<link href="../assets/css/navbaruser.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    background:#f6f7fb;
    font-family:Poppins, sans-serif;
}

.history-card{
    background:#fff;
    border-radius:14px;
    padding:20px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.table-wrapper{
    max-height:300px;  
    overflow-y:auto;
}

.badge-diproses{ background:#ffc107; color:#000; }
.badge-dipinjam{ background:#0d6efd; }
.badge-kembali{ background:#198754; }
.badge-terlambat{ background:#dc3545; }
</style>
</head>

<body>

<!-- NAVBAR -->
<?php include __DIR__ . '/navbar.php'; ?>

<!-- CONTENT -->
<div class="container mt-5">
    <h4 class="fw-bold mb-3">📚 History Peminjaman</h4>

    <div class="history-card">
        <div class="table-wrapper">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tempo</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>

                <?php 
                $no = 1;
                $today = date('Y-m-d');

                while($row = $query->fetch_assoc()):
                    $tempo = date('Y-m-d', strtotime($row['tgl_pinjam'].' +7 days'));

                    $terlambat = (
                        $row['status'] === 'dipinjam' &&
                        $today > $tempo
                    );
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_buku']) ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
                        <td><?= date('d-m-Y', strtotime($tempo)) ?></td>

                        <td>
                            <?php if($row['status']=='diproses'): ?>
                                <span class="badge badge-diproses">Diproses</span>
                            <?php elseif($row['status']=='dipinjam'): ?>
                                <span class="badge badge-dipinjam">Dipinjam</span>
                            <?php else: ?>
                                <span class="badge badge-kembali">Dikembalikan</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($row['status']=='dikembalikan' && $row['tgl_dikembalikan']): ?>
                                <span class="text-success">
                                    Dikembalikan:
                                    <?= date('d-m-Y', strtotime($row['tgl_dikembalikan'])) ?>
                                </span>

                            <?php elseif($terlambat): ?>
                                <span class="badge badge-terlambat">⚠ Terlambat</span>

                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
