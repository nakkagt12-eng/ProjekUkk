<?php
session_start();
require_once __DIR__ . '/../config/config.php';

/* ================= PROTEKSI ADMIN ================= */
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

/* ================= PAGINATION ================= */
$limit = 5;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = ($page < 1) ? 1 : $page;
$start = ($page - 1) * $limit;

/* ================= SEARCH & FILTER ================= */
$keyword = $_GET['search'] ?? '';
$type    = $_GET['type'] ?? '';
$like    = "%$keyword%";

/* ================= QUERY DATA ================= */
$sql = "
    SELECT 
        p.id_pinjam,
        p.username,
        b.nama_buku,
        t.nama_type AS kategori,
        p.tgl_pinjam,
        p.tgl_kembali,
        p.tgl_dikembalikan,
        p.status
    FROM pinjam p
    JOIN buku b ON p.id_buku = b.id_buku
    JOIN type_buku t ON b.id_type = t.id_type
    WHERE (p.username LIKE ? OR b.nama_buku LIKE ?)
";

if (!empty($type)) {
    $sql .= " AND t.id_type = ?";
}

$sql .= " ORDER BY p.id_pinjam DESC LIMIT ?, ?";

$stmt = $conn->prepare($sql);

if (!empty($type)) {
    $stmt->bind_param("ssiii", $like, $like, $type, $start, $limit);
} else {
    $stmt->bind_param("ssii", $like, $like, $start, $limit);
}

$stmt->execute();
$result = $stmt->get_result();

/* ================= TOTAL DATA ================= */
$countSql = "
    SELECT COUNT(*) AS total
    FROM pinjam p
    JOIN buku b ON p.id_buku = b.id_buku
    JOIN type_buku t ON b.id_type = t.id_type
    WHERE (p.username LIKE ? OR b.nama_buku LIKE ?)
";

if (!empty($type)) {
    $countSql .= " AND t.id_type = ?";
}

$countStmt = $conn->prepare($countSql);

if (!empty($type)) {
    $countStmt->bind_param("ssi", $like, $like, $type);
} else {
    $countStmt->bind_param("ss", $like, $like);
}

$countStmt->execute();
$totalData = $countStmt->get_result()->fetch_assoc()['total'];
$totalPage = ceil($totalData / $limit);

/* ================= DATA TYPE ================= */
$typeBuku = $conn->query("SELECT * FROM type_buku ORDER BY nama_type ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin | Pinjaman</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f6fb;
}
.table-wrapper{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 6px 20px rgba(0,0,0,.08);
}
table th{
    background:#e5e7eb;
    text-align:center;
}
table td{
    vertical-align:middle;
    text-align:center;
}
.dashboard-container{
    display:flex;
}
</style>
</head>
<body>
<div class="dashboard-container">
<?php include __DIR__ . "/navbar.php"; ?>
<main>
<div class="container mt-4">
<h4 class="mb-3">📚 Daftar Pinjaman Buku</h4>

<!-- FILTER -->
<form method="GET" class="row g-2 mb-3 align-items-end">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control"
               placeholder="Cari username / buku..."
               value="<?= htmlspecialchars($keyword) ?>">
    </div>

    <div class="col-md-3">
        <select name="type" class="form-select">
            <option value="">-- Semua Kategori --</option>
            <?php while($t = $typeBuku->fetch_assoc()): ?>
                <option value="<?= $t['id_type'] ?>"
                    <?= ($type == $t['id_type']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['nama_type']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">
            <i class="bi bi-search"></i> Filter
        </button>
    </div>

    <div class="col-md-3">
        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </button>
    </div>
</form>

<div class="table-wrapper">
<table class="table table-bordered table-sm">
<thead>
<tr>
    <th>No</th>
    <th>Username</th>
    <th>Buku</th>
    <th>Kategori</th>
    <th>Tgl Pinjam</th>
    <th>Rencana Kembali</th>
    <th>Dikembalikan</th>
    <th>Status</th>
    <th width="160">Aksi</th>
</tr>
</thead>
<tbody>

<?php if($result->num_rows > 0): 
$no = $start + 1;
while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['nama_buku']) ?></td>
    <td><?= htmlspecialchars($row['kategori']) ?></td>
    <td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
    <td><?= date('d-m-Y', strtotime($row['tgl_kembali'])) ?></td>
    <td>
        <?= $row['tgl_dikembalikan'] 
            ? date('d-m-Y', strtotime($row['tgl_dikembalikan'])) 
            : '<span class="badge bg-warning">Belum</span>' ?>
    </td>
    <td>
        <?php
        if($row['status']=='diproses'){
            echo '<span class="badge bg-warning">Diproses</span>';
        }elseif($row['status']=='dipinjam'){
            echo '<span class="badge bg-primary">Dipinjam</span>';
        }else{
            echo '<span class="badge bg-success">Dikembalikan</span>';
        }
        ?>
    </td>
    <td>
        <?php if($row['status']=='diproses'): ?>
            <a href="aksi_pinjam.php?id=<?= $row['id_pinjam'] ?>" class="btn btn-success btn-sm">Setujui</a>
        <?php elseif($row['status']=='dipinjam'): ?>
            <a href="aksi_kembali.php?id=<?= $row['id_pinjam'] ?>" class="btn btn-warning btn-sm">Kembalikan</a>
        <?php else: ?>
            <span class="badge bg-secondary">Selesai</span>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; else: ?>
<tr>
<td colspan="9" class="text-muted">Belum ada data pinjaman</td>
</tr>
<?php endif; ?>

</tbody>
</table>
</div>

<!-- MODAL EXPORT -->
<div class="modal fade" id="exportModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="GET" action="export_excel.php">
        <div class="modal-header">
          <h5 class="modal-title">📥 Export Excel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <input type="hidden" name="search" value="<?= htmlspecialchars($keyword) ?>">
          <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

          <div class="mb-3">
            <label class="form-label">Bulan</label>
            <input type="month" name="bulan" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nama File</label>
            <input type="text" name="filename" class="form-control"
                   placeholder="laporan_pinjaman_februari" required>
          </div>

        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-success">Download</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</main>
</div>
</body>
</html>
