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
    $nama = trim($_POST['nama_type']);
    $penjelasan = trim($_POST['penjelasan']);

    $stmt = $conn->prepare(
        "INSERT INTO type_buku (nama_type, penjelasan) VALUES (?, ?)"
    );
    $stmt->bind_param("ss", $nama, $penjelasan);
    $stmt->execute();

    header("Location: type_buku.php");
    exit;
}

/* ================= UPDATE ================= */
if (isset($_POST['update'])) {
    $id = $_POST['id_type'];
    $nama = trim($_POST['nama_type']);
    $penjelasan = trim($_POST['penjelasan']);

    $stmt = $conn->prepare(
        "UPDATE type_buku SET nama_type=?, penjelasan=? WHERE id_type=?"
    );
    $stmt->bind_param("ssi", $nama, $penjelasan, $id);
    $stmt->execute();

    header("Location: type_buku.php");
    exit;
}

/* ================= HAPUS ================= */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $stmt = $conn->prepare("DELETE FROM type_buku WHERE id_type=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: type_buku.php");
    exit;
}

/* ================= SEARCH ================= */
$keyword = $_GET['search'] ?? '';
$like = "%$keyword%";

$stmt = $conn->prepare(
    "SELECT * FROM type_buku WHERE nama_type LIKE ? ORDER BY id_type DESC"
);
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Type Buku | SCLibrary</title>
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
}.dashboard-container{
    display:flex;
}
</style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
 <div class="dashboard-container">
<?php include 'navbar.php'; ?>
<main>
<!-- ===== CONTENT ===== -->
<div class="container mt-5">

<h4 class="page-title mb-3">Data Type Buku</h4>

<form method="GET" class="mb-3">
<div class="input-group" style="max-width:320px">
<span class="input-group-text"><i class="bi bi-search"></i></span>
<input type="text" name="search" class="form-control"
placeholder="Cari type buku..."
value="<?= htmlspecialchars($keyword) ?>">
</div>
</form>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
<i class="bi bi-plus-circle"></i> Tambah Type Buku
</button>

<table class="table table-bordered text-center align-middle">
<thead>
<tr>
<th width="60">No</th>
<th width="220">Nama Type</th>
<th>Penjelasan</th>
<th width="140">Aksi</th>
</tr>
</thead>
<tbody>

<?php if($result->num_rows > 0): $no=1; ?>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $no++ ?></td>
<td><?= htmlspecialchars($row['nama_type']) ?></td>
<td class="text-start"><?= htmlspecialchars($row['penjelasan']) ?></td>
<td>
<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#edit<?= $row['id_type'] ?>">
<i class="bi bi-pencil"></i>
</button>

<a href="?hapus=<?= $row['id_type'] ?>"
onclick="return confirm('Hapus type buku ini?')"
class="btn btn-danger btn-sm">
<i class="bi bi-trash"></i>
</a>
</td>
</tr>

<!-- ===== MODAL EDIT ===== -->
<div class="modal fade" id="edit<?= $row['id_type'] ?>" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<form method="POST">
<input type="hidden" name="id_type" value="<?= $row['id_type'] ?>">

<div class="modal-header">
<h5 class="modal-title">Edit Type Buku</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<div class="mb-3">
<label class="form-label">Nama Type</label>
<input type="text" name="nama_type" class="form-control"
value="<?= htmlspecialchars($row['nama_type']) ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Penjelasan</label>
<textarea name="penjelasan" class="form-control" rows="4" required><?= htmlspecialchars($row['penjelasan']) ?></textarea>
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
<td colspan="4" class="text-muted">Data type buku belum tersedia</td>
</tr>
<?php endif; ?>

</tbody>
</table>

</div>

<!-- ===== MODAL TAMBAH ===== -->
<div class="modal fade" id="modalTambah" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<form method="POST">

<div class="modal-header">
<h5 class="modal-title">Tambah Type Buku</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<div class="mb-3">
<label class="form-label">Nama Type</label>
<input type="text" name="nama_type" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Penjelasan</label>
<textarea name="penjelasan" class="form-control" rows="4" required></textarea>
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
