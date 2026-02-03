<?php
session_start();
require_once "../config/config.php";

/* ================= PROTEKSI ADMIN ================= */
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

/* ================= TAMBAH ================= */
if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_genre']);
    $pengertian = trim($_POST['pengertian']);

    $stmt = $conn->prepare(
        "INSERT INTO genre (nama_genre, pengertian) VALUES (?, ?)"
    );
    $stmt->bind_param("ss", $nama, $pengertian);
    $stmt->execute();

    header("Location: genre.php");
    exit;
}

/* ================= UPDATE ================= */
if (isset($_POST['update'])) {
    $id = $_POST['id_genre'];
    $nama = trim($_POST['nama_genre']);
    $pengertian = trim($_POST['pengertian']);

    $stmt = $conn->prepare(
        "UPDATE genre SET nama_genre=?, pengertian=? WHERE id_genre=?"
    );
    $stmt->bind_param("ssi", $nama, $pengertian, $id);
    $stmt->execute();

    header("Location: genre.php");
    exit;
}

/* ================= HAPUS ================= */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $stmt = $conn->prepare("DELETE FROM genre WHERE id_genre=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: genre.php");
    exit;
}

/* ================= PAGINATION ================= */
$limit = 5;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = ($page < 1) ? 1 : $page;
$start = ($page - 1) * $limit;

/* ================= SEARCH ================= */
$keyword = $_GET['search'] ?? '';
$like = "%$keyword%";

$stmt = $conn->prepare(
    "SELECT * FROM genre 
     WHERE nama_genre LIKE ? 
     ORDER BY id_genre DESC 
     LIMIT ?, ?"
);
$stmt->bind_param("sii", $like, $start, $limit);
$stmt->execute();
$result = $stmt->get_result();

/* TOTAL DATA */
$countStmt = $conn->prepare(
    "SELECT COUNT(*) as total FROM genre WHERE nama_genre LIKE ?"
);
$countStmt->bind_param("s", $like);
$countStmt->execute();
$totalData = $countStmt->get_result()->fetch_assoc()['total'];
$totalPage = ceil($totalData / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Genre | SCLibrary</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f5f6fa;
}
.page-title{
    font-weight:600;
}
.table thead{
    background:#e9ecef;
}
.dashboard-container{
    display:flex;
}
</style>
</head>
<body>
<div class="dashboard-container">
<?php include 'navbar.php'; ?>
<main>
<div class="container mt-5">

<h4 class="page-title mb-3">Data Genre</h4>

<form method="GET" class="mb-3">
<div class="input-group" style="max-width:320px">
<span class="input-group-text"><i class="bi bi-search"></i></span>
<input type="text" name="search" class="form-control"
placeholder="Cari genre..."
value="<?= htmlspecialchars($keyword) ?>">
</div>
</form>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
<i class="bi bi-plus-circle"></i> Tambah Genre
</button>

<table class="table table-bordered align-middle text-center">
<thead>
<tr>
<th width="60">No</th>
<th width="220">Nama Genre</th>
<th>Pengertian</th>
<th width="140">Aksi</th>
</tr>
</thead>
<tbody>

<?php if($result->num_rows > 0): 
$no = $start + 1;
while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $no++ ?></td>
<td><?= htmlspecialchars($row['nama_genre']) ?></td>
<td class="text-start"><?= htmlspecialchars($row['pengertian']) ?></td>
<td>
<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#edit<?= $row['id_genre'] ?>">
<i class="bi bi-pencil"></i>
</button>

<a href="?hapus=<?= $row['id_genre'] ?>"
onclick="return confirm('Hapus genre ini?')"
class="btn btn-danger btn-sm">
<i class="bi bi-trash"></i>
</a>
</td>
</tr>

<!-- MODAL EDIT -->
<div class="modal fade" id="edit<?= $row['id_genre'] ?>" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<form method="POST">
<input type="hidden" name="id_genre" value="<?= $row['id_genre'] ?>">

<div class="modal-header">
<h5 class="modal-title">Edit Genre</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<div class="mb-3">
<label class="form-label">Nama Genre</label>
<input type="text" name="nama_genre" class="form-control"
value="<?= htmlspecialchars($row['nama_genre']) ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Pengertian</label>
<textarea name="pengertian" class="form-control" rows="4" required><?= htmlspecialchars($row['pengertian']) ?></textarea>
</div>
</div>

<div class="modal-footer">
<button type="submit" name="update" class="btn btn-primary">Update</button>
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
</div>
</form>
</div>
</div>
</div>

<?php endwhile; else: ?>
<tr>
<td colspan="4" class="text-muted">Data genre belum tersedia</td>
</tr>
<?php endif; ?>

</tbody>
</table>

<!-- PAGINATION -->
<?php if($totalPage > 1): ?>
<nav>
<ul class="pagination justify-content-center">

<li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
<a class="page-link" href="?search=<?= $keyword ?>&page=<?= $page-1 ?>">‹</a>
</li>

<?php for($i=1; $i<=$totalPage; $i++): ?>
<li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
<a class="page-link" href="?search=<?= $keyword ?>&page=<?= $i ?>">
<?= $i ?>
</a>
</li>
<?php endfor; ?>

<li class="page-item <?= ($page >= $totalPage) ? 'disabled' : '' ?>">
<a class="page-link" href="?search=<?= $keyword ?>&page=<?= $page+1 ?>">›</a>
</li>

</ul>
</nav>
<?php endif; ?>

</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<form method="POST">

<div class="modal-header">
<h5 class="modal-title">Tambah Genre</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<div class="mb-3">
<label class="form-label">Nama Genre</label>
<input type="text" name="nama_genre" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Pengertian</label>
<textarea name="pengertian" class="form-control" rows="4" required></textarea>
</div>
</div>

<div class="modal-footer">
<button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
