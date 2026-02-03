<?php
session_start();
require_once __DIR__ . '/../config/config.php';

/* ===== PROTEKSI USER ===== */
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit;
}

$id_buku  = intval($_GET['id']);
$username = $_SESSION['username'];

/* ===== DETAIL BUKU ===== */
$detail = $conn->query("
    SELECT 
        buku.*, 
        type_buku.nama_type,
        GROUP_CONCAT(genre.nama_genre SEPARATOR ', ') AS genre
    FROM buku
    LEFT JOIN type_buku ON buku.id_type = type_buku.id_type
    LEFT JOIN buku_genre ON buku.id_buku = buku_genre.id_buku
    LEFT JOIN genre ON buku_genre.id_genre = genre.id_genre
    WHERE buku.id_buku = '$id_buku'
    GROUP BY buku.id_buku
")->fetch_assoc();

if (!$detail) {
    header("Location: list.php");
    exit;
}

/* ===== SIMPAN REVIEW (1x per user) ===== */
if (isset($_POST['kirim_review'])) {
    $rating   = intval($_POST['rating']);
    $komentar = $conn->real_escape_string($_POST['komentar']);

    $cek = $conn->query("
        SELECT * FROM review 
        WHERE id_buku='$id_buku' 
        AND username='$username'
    ");

    if ($cek->num_rows == 0) {
        $conn->query("
            INSERT INTO review (id_buku, username, rating, komentar)
            VALUES ('$id_buku', '$username', '$rating', '$komentar')
        ");
    }

    header("Location: viewall.php?id=$id_buku");
    exit;
}

/* ===== DATA REVIEW ===== */
$reviews = $conn->query("
    SELECT * FROM review
    WHERE id_buku='$id_buku'
    ORDER BY created_at DESC
");

/* ===== RATA-RATA ===== */
$avg = $conn->query("
    SELECT AVG(rating) AS rata 
    FROM review 
    WHERE id_buku='$id_buku'
")->fetch_assoc();

/* ===== CEK REVIEW USER ===== */
$cekReviewUser = $conn->query("
    SELECT * FROM review 
    WHERE id_buku='$id_buku' 
    AND username='$username'
");
$sudahReview = $cekReviewUser->num_rows > 0;
/* ===== UPDATE REVIEW ===== */
if (isset($_POST['update_review'])) {
    $id_review = intval($_POST['id_review']);
    $rating    = intval($_POST['rating']);
    $komentar  = $conn->real_escape_string($_POST['komentar']);

    $conn->query("
        UPDATE review 
        SET rating='$rating', komentar='$komentar'
        WHERE id_review='$id_review'
        AND username='$username'
    ");

    header("Location: viewall.php?id=$id_buku");
    exit;
}
/* ===== HAPUS REVIEW ===== */
if (isset($_POST['hapus_review'])) {
    $id_review = intval($_POST['id_review']);

    $conn->query("
        DELETE FROM review 
        WHERE id_review='$id_review'
        AND username='$username'
    ");

    header("Location: viewall.php?id=$id_buku");
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($detail['nama_buku']) ?> | SCLibrary</title>
<link href="../assets/css/navbaruser.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{background:#f6f7fb;font-family:Poppins,sans-serif}
.detail-box{background:#fff;border-radius:18px;padding:30px}
.cover{width:260px;height:360px;border:1px solid #ddd;border-radius:16px;display:flex;align-items:center;justify-content:center}
.cover img{width:160px;height:220px;object-fit:cover}
.badge-kategori{background:#e5e7eb;border-radius:20px;padding:6px 14px;font-size:12px}

.review-box{background:#fff;border-radius:16px;padding:25px}
.review-item{background:#f1f3f5;border-radius:12px;padding:14px;margin-bottom:12px}

.star-pilih{font-size:28px;cursor:pointer;color:#ccc}
.star-pilih.active{color:#f5b50a}
.star-view{color:#f5b50a}
</style>
</head>
<!-- MODAL EDIT REVIEW -->
<div class="modal fade" id="editReviewModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title">Edit Review</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id_review" id="edit-id-review">

          <label class="form-label">Rating</label>
          <select name="rating" id="edit-rating" class="form-select mb-3" required>
            <option value="5">⭐⭐⭐⭐⭐</option>
            <option value="4">⭐⭐⭐⭐</option>
            <option value="3">⭐⭐⭐</option>
            <option value="2">⭐⭐</option>
            <option value="1">⭐</option>
          </select>

          <label class="form-label">Komentar</label>
          <textarea name="komentar" id="edit-komentar"
            class="form-control" rows="3" required></textarea>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Batal
          </button>
          <button type="submit" name="update_review" class="btn btn-primary">
            Simpan Perubahan
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<body>

<?php include __DIR__ . '/navbar.php'; ?>

<div class="container mt-5">

<a href="list.php" class="btn btn-outline-primary mb-4">
    <i class="bi bi-arrow-left"></i> Kembali
</a>

<div class="detail-box mb-4">
<span class="badge-kategori"><?= $detail['nama_type'] ?></span>

<div class="row mt-4">
<div class="col-md-4">
<div class="cover">
<img src="../assets/img/buku/<?= $detail['img'] ?>">
</div>
</div>

<div class="col-md-8">
<h3><?= htmlspecialchars($detail['nama_buku']) ?></h3>
<p class="text-muted"><?= htmlspecialchars($detail['penjelasan']) ?></p>

<p><strong>Genre:</strong> <?= $detail['genre'] ?></p>
<p><strong>Penulis:</strong> <?= htmlspecialchars($detail['penulis']) ?></p>
<p><strong>Stok:</strong> <?= $detail['stok'] ?></p>
<p><strong>Rating:</strong> <?= $avg['rata'] ? round($avg['rata'],1) : 0 ?> / 5 ⭐</p>

<a href="pinjaman.php?id=<?= $detail['id_buku'] ?>" class="btn btn-primary rounded-pill px-4">
Pinjam
</a>
</div>
</div>
</div>

 <div class="review-box">
<h5>Review Buku</h5>

<?php if(!$sudahReview): ?>
<form method="post" class="mb-4">
<input type="hidden" name="rating" id="rating" required>

<div class="mb-2">
<span class="star-pilih" data-val="1">★</span>
<span class="star-pilih" data-val="2">★</span>
<span class="star-pilih" data-val="3">★</span>
<span class="star-pilih" data-val="4">★</span>
<span class="star-pilih" data-val="5">★</span>
</div>

<textarea name="komentar" class="form-control mb-3"
placeholder="Tulis pendapatmu..." required></textarea>

<button name="kirim_review" class="btn btn-success rounded-pill px-4">
Kirim Review
</button>
</form>
<?php else: ?>
<div class="alert alert-info">Kamu sudah memberikan review.</div>
<?php endif; ?>

<?php if($reviews->num_rows == 0): ?>
<p class="text-muted">Belum ada review.</p>
<?php endif; ?>

<?php while($r = $reviews->fetch_assoc()): ?>
<div class="review-item">
<strong><?= htmlspecialchars($r['username']) ?></strong>
<span class="star-view ms-2"><?= str_repeat('★',$r['rating']) ?></span>

<p class="mt-2"><?= htmlspecialchars($r['komentar']) ?></p>
<small class="text-muted"><?= $r['created_at'] ?></small>

<?php if($r['username'] == $username): ?>
<div class="mt-2">
<button 
    class="btn btn-sm btn-warning"
    data-bs-toggle="modal"
    data-bs-target="#editReviewModal"
    data-id="<?= $r['id_review'] ?>"
    data-rating="<?= $r['rating'] ?>"
    data-komentar="<?= htmlspecialchars($r['komentar']) ?>">
    Edit
</button>

<a href="hapus  .php?id=<?= $r['id_review'] ?>&buku=<?= $id_buku ?>"
onclick="return confirm('Hapus review?')"
class="btn btn-sm btn-danger">Hapus</a>
</div>
<?php endif; ?>
</div>
<?php endwhile; ?>

</div>
</div>

<script>
const stars=document.querySelectorAll('.star-pilih');
const input=document.getElementById('rating');
stars.forEach((s,i)=>{
s.addEventListener('click',()=>{
input.value=i+1;
stars.forEach((x,j)=>x.classList.toggle('active',j<=i));
});
});
</script>
<script>
const editModal = document.getElementById('editReviewModal');

editModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    document.getElementById('edit-id-review').value = button.getAttribute('data-id');
    document.getElementById('edit-rating').value    = button.getAttribute('data-rating');
    document.getElementById('edit-komentar').value  = button.getAttribute('data-komentar');
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
