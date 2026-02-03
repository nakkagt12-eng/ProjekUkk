<?php
session_start();
$conn = new mysqli("localhost","root","","sclibary");
if($conn->connect_error){ die("Koneksi gagal"); }

/* ================= PROTEKSI ADMIN ================= */
if(!isset($_SESSION['username']) || $_SESSION['role']!=='admin'){
    header("Location: ../auth/login.php");
    exit;
}

/* ================= TAMBAH ================= */
if(isset($_POST['simpan'])){
    $nama = $_POST['nama_buku'];
    $penulis = $_POST['penulis'] ?? null;
    $stok = $_POST['stok'];
    $penjelasan = $_POST['penjelasan'];
    $type = $_POST['id_type'];
    $genre = $_POST['genre'] ?? [];

    $img = $_FILES['img']['name'];
    if($img){
        move_uploaded_file($_FILES['img']['tmp_name'], "../assets/img/buku/".$img);
    }

    $conn->query("INSERT INTO buku
        (nama_buku, penulis, id_type, penjelasan, img, stok)
        VALUES
        ('$nama','$penulis','$type','$penjelasan','$img','$stok')
    ");

    $id_buku = $conn->insert_id;
    foreach($genre as $g){
        $conn->query("INSERT INTO buku_genre VALUES('$id_buku','$g')");
    }
}

/* ================= UPDATE ================= */
if(isset($_POST['update'])){
    $id = $_POST['id_buku'];
    $nama = $_POST['nama_buku'];
    $penulis = $_POST['penulis'];
    $stok = $_POST['stok'];
    $penjelasan = $_POST['penjelasan'];
    $type = $_POST['id_type'];

    if(!empty($_FILES['img']['name'])){
        $img = $_FILES['img']['name'];
        move_uploaded_file($_FILES['img']['tmp_name'], "../assets/img/buku/".$img);
        $conn->query("UPDATE buku SET img='$img' WHERE id_buku='$id'");
    }

    $conn->query("UPDATE buku SET
        nama_buku='$nama',
        penulis='$penulis',
        id_type='$type',
        penjelasan='$penjelasan',
        stok='$stok'
        WHERE id_buku='$id'
    ");

    $conn->query("DELETE FROM buku_genre WHERE id_buku='$id'");
    if(isset($_POST['genre'])){
        foreach($_POST['genre'] as $g){
            $conn->query("INSERT INTO buku_genre VALUES('$id','$g')");
        }
    }
}

/* ================= HAPUS ================= */
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM buku_genre WHERE id_buku='$id'");
    $conn->query("DELETE FROM buku WHERE id_buku='$id'");
}

/* ================= SEARCH & FILTER ================= */
$search = $_GET['search'] ?? '';
$filterType = $_GET['type'] ?? '';
$filterGenre = $_GET['genre'] ?? '';

$where = "WHERE 1=1";
if($search){
    $where .= " AND (b.nama_buku LIKE '%$search%' OR b.penulis LIKE '%$search%')";
}
if($filterType){
    $where .= " AND b.id_type='$filterType'";
}
if($filterGenre){
    $where .= " AND g.id_genre='$filterGenre'";
}

/* ================= PAGINATION ================= */
$limit = 5;
$page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
$offset = ($page-1)*$limit;

$totalData = $conn->query("
    SELECT COUNT(DISTINCT b.id_buku) total
    FROM buku b
    LEFT JOIN buku_genre bg ON b.id_buku = bg.id_buku
    LEFT JOIN genre g ON bg.id_genre = g.id_genre
    $where
")->fetch_assoc()['total'];

$totalPage = ceil($totalData/$limit);

/* ================= DATA ================= */
$buku = $conn->query("
    SELECT b.*, tb.nama_type,
    GROUP_CONCAT(DISTINCT g.nama_genre SEPARATOR ', ') genre
    FROM buku b
    LEFT JOIN type_buku tb ON b.id_type = tb.id_type
    LEFT JOIN buku_genre bg ON b.id_buku = bg.id_buku
    LEFT JOIN genre g ON bg.id_genre = g.id_genre
    $where
    GROUP BY b.id_buku
    ORDER BY b.id_buku DESC
    LIMIT $limit OFFSET $offset
");

$typeBuku = $conn->query("SELECT * FROM type_buku");
$genreBuku = $conn->query("SELECT * FROM genre");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Buku | SCLibrary</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{ background:#f5f6fa; }
.cover{ width:60px;height:80px;object-fit:cover;border-radius:4px }
.dashboard-container{
    display:flex;
}
</style>
</head>

<body>
    <div class="dashboard-container">
<?php include 'navbar.php'; ?>
<main>
<div class="container mt-4">

<div class="d-flex justify-content-between mb-3">
<h4>📚 Data Buku</h4>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah">
<i class="bi bi-plus-circle"></i> Tambah Buku
</button>
</div>

<!-- SEARCH & FILTER -->
<form method="GET" class="row g-2 mb-3">
<div class="col-md-4">
<input type="text" name="search" class="form-control" placeholder="Cari judul / penulis"
value="<?= htmlspecialchars($search) ?>">
</div>

<div class="col-md-3">
<select name="type" class="form-control">
<option value="">-- Semua Type --</option>
<?php
$typeFilter=$conn->query("SELECT * FROM type_buku");
while($t=$typeFilter->fetch_assoc()):
?>
<option value="<?= $t['id_type'] ?>" <?= ($filterType==$t['id_type'])?'selected':'' ?>>
<?= $t['nama_type'] ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-3">
<select name="genre" class="form-control">
<option value="">-- Semua Genre --</option>
<?php
$genreFilter=$conn->query("SELECT * FROM genre");
while($g=$genreFilter->fetch_assoc()):
?>
<option value="<?= $g['id_genre'] ?>" <?= ($filterGenre==$g['id_genre'])?'selected':'' ?>>
<?= $g['nama_genre'] ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-2 d-grid">
<button class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
</div>
</form>

<div class="card">
<div class="card-body p-0">
<table class="table table-hover text-center align-middle mb-0">
<thead class="table-dark">
<tr>
<th>No</th>
<th>Cover</th>
<th>Judul</th>
<th>Penulis</th>
<th>Type</th>
<th>Genre</th>
<th>Stok</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>
<?php $no=$offset+1; while($r=$buku->fetch_assoc()): ?>
<tr>
<td><?= $no++ ?></td>
<td><img src="../assets/img/buku/<?= $r['img'] ?>" class="cover"></td>
<td><?= $r['nama_buku'] ?></td>
<td><?= $r['penulis'] ?></td>
<td><?= $r['nama_type'] ?></td>
<td><?= $r['genre'] ?></td>
<td><span class="badge bg-success"><?= $r['stok'] ?></span></td>
<td>
<button class="btn btn-warning btn-sm" data-bs-toggle="modal"
data-bs-target="#edit<?= $r['id_buku'] ?>">
<i class="bi bi-pencil"></i>
</button>
<a href="?hapus=<?= $r['id_buku'] ?>" onclick="return confirm('Hapus buku?')"
class="btn btn-danger btn-sm">
<i class="bi bi-trash"></i>
</a>
</td>
</tr>

<?php
$genreEdit=[];
$qg=$conn->query("SELECT id_genre FROM buku_genre WHERE id_buku='".$r['id_buku']."'");
while($g=$qg->fetch_assoc()){ $genreEdit[]=$g['id_genre']; }
?>

<!-- MODAL EDIT -->
<div class="modal fade" id="edit<?= $r['id_buku'] ?>">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="id_buku" value="<?= $r['id_buku'] ?>">

<div class="modal-header">
<h5>Edit Buku</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<input name="nama_buku" class="form-control mb-2" value="<?= $r['nama_buku'] ?>" required>
<input name="penulis" class="form-control mb-2" value="<?= $r['penulis'] ?>">

<select name="id_type" class="form-control mb-2" required>
<?php
$typeEdit=$conn->query("SELECT * FROM type_buku");
while($t=$typeEdit->fetch_assoc()):
?>
<option value="<?= $t['id_type'] ?>" <?= ($t['id_type']==$r['id_type'])?'selected':'' ?>>
<?= $t['nama_type'] ?>
</option>
<?php endwhile; ?>
</select>

<label>Genre</label>
<div class="row">
<?php
$genreAll=$conn->query("SELECT * FROM genre");
while($g=$genreAll->fetch_assoc()):
?>
<div class="col-md-4">
<div class="form-check">
<input class="form-check-input" type="checkbox" name="genre[]"
value="<?= $g['id_genre'] ?>" <?= in_array($g['id_genre'],$genreEdit)?'checked':'' ?>>
<label class="form-check-label"><?= $g['nama_genre'] ?></label>
</div>
</div>
<?php endwhile; ?>
</div>

<input name="stok" type="number" class="form-control mt-2 mb-2" value="<?= $r['stok'] ?>" required>
<textarea name="penjelasan" class="form-control mb-2"><?= $r['penjelasan'] ?></textarea>
<input type="file" name="img" class="form-control">
</div>

<div class="modal-footer">
<button name="update" class="btn btn-warning">Update</button>
</div>
</form>
</div>
</div>
</div>

<?php endwhile; ?>
</tbody>
</table>
</div>
</div>

<!-- PAGINATION -->
<nav class="mt-3">
<ul class="pagination justify-content-center">
<?php for($i=1;$i<=$totalPage;$i++): ?>
<li class="page-item <?= ($i==$page)?'active':'' ?>">
<a class="page-link"
href="?page=<?= $i ?>&search=<?= $search ?>&type=<?= $filterType ?>&genre=<?= $filterGenre ?>">
<?= $i ?>
</a>
</li>
<?php endfor; ?>
</ul>
</nav>

</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="tambah">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<form method="POST" enctype="multipart/form-data">
<div class="modal-header">
<h5>Tambah Buku</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<input name="nama_buku" class="form-control mb-2" placeholder="Nama Buku" required>
<input name="penulis" class="form-control mb-2" placeholder="Penulis">

<select name="id_type" class="form-control mb-2" required>
<option value="">-- Pilih Type --</option>
<?php mysqli_data_seek($typeBuku,0); while($t=$typeBuku->fetch_assoc()): ?>
<option value="<?= $t['id_type'] ?>"><?= $t['nama_type'] ?></option>
<?php endwhile; ?>
</select>

<label>Genre</label>
<div class="row">
<?php mysqli_data_seek($genreBuku,0); while($g=$genreBuku->fetch_assoc()): ?>
<div class="col-md-4">
<div class="form-check">
<input class="form-check-input" type="checkbox" name="genre[]" value="<?= $g['id_genre'] ?>">
<label class="form-check-label"><?= $g['nama_genre'] ?></label>
</div>
</div>
<?php endwhile; ?>
</div>

<input name="stok" type="number" class="form-control mt-2 mb-2" placeholder="Stok" required>
<textarea name="penjelasan" class="form-control mb-2" placeholder="Penjelasan"></textarea>
<input type="file" name="img" class="form-control">
</div>
<div class="modal-footer">
<button name="simpan" class="btn btn-primary">Simpan</button>
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
