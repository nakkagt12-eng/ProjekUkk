<?php
session_start();
require_once "../config/config.php";

/* ================= PROTEKSI LOGIN ================= */
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
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

/* ================= DATA USER ================= */
$stmt = $conn->prepare(
    "SELECT id, username, email, role
     FROM users
     WHERE role != 'admin'
       AND (username LIKE ? OR email LIKE ?)
     ORDER BY id DESC
     LIMIT ?, ?"
);
$stmt->bind_param("ssii", $like, $like, $start, $limit);
$stmt->execute();
$result = $stmt->get_result();

/* ================= TOTAL DATA ================= */
$countStmt = $conn->prepare(
    "SELECT COUNT(*) as total
     FROM users
     WHERE role != 'admin'
       AND (username LIKE ? OR email LIKE ?)"
);
$countStmt->bind_param("ss", $like, $like);
$countStmt->execute();
$totalData = $countStmt->get_result()->fetch_assoc()['total'];
$totalPage = ceil($totalData / $limit);

/* ================= DELETE USER ================= */
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);

    $stmt = $conn->prepare(
        "DELETE FROM users WHERE id=? AND role!='admin'"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: user.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data User | SCLibrary</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
body{font-family:'Poppins',sans-serif;background:#f5f6fa}
.page-title{font-weight:600}
.table thead{background:#e5e5e5}
.search-box{max-width:340px}

/* NAVBAR */
.sclib-navbar{background:linear-gradient(90deg,#43B6C0,#003099)}
.nav-link{color:#fff!important;font-size:14px;margin-left:12px}
.nav-link.active{font-weight:600;border-bottom:2px solid #fff}
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
    <h4 class="page-title mb-3">Data User</h4>

    <form method="GET" class="mb-3">
        <div class="input-group search-box">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control"
                   placeholder="Cari username / email..."
                   value="<?= htmlspecialchars($keyword) ?>">
        </div>
    </form>

    <table class="table table-bordered text-center align-middle">
        <thead>
            <tr>
                <th width="70">No</th>
                <th>Username</th>
                <th>Email</th>
                <th width="120">Role</th>
                <th width="120">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if($result->num_rows > 0): 
            $no = $start + 1;
            while($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>User</td>
                <td>
                    <a href="?hapus=<?= $row['id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus user ini?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endwhile; else: ?>
            <tr>
                <td colspan="5" class="text-muted">Data user belum ada</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- PAGINATION -->
    <?php if($totalPage > 1): ?>
    <nav>
        <ul class="pagination justify-content-center">

            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="?search=<?= $keyword ?>&page=<?= $page-1 ?>">‹</a>
            </li>

            <?php for($i=1; $i<=$totalPage; $i++): ?>
            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                <a class="page-link"
                   href="?search=<?= $keyword ?>&page=<?= $i ?>">
                   <?= $i ?>
                </a>
            </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page >= $totalPage) ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="?search=<?= $keyword ?>&page=<?= $page+1 ?>">›</a>
            </li>

        </ul>
    </nav>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</main>
</div>
</body>
</html>
