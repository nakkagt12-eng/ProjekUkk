<?php
$current = basename($_SERVER['PHP_SELF']);
function active($page){
    global $current;
    return $current == $page ? 'active fw-semibold' : '';
}
?>

<style>
/* ===== SIDEBAR ===== */
body{
    background:#f4f6fb;
}

.sidebar{
    width:260px;
    min-height:100vh;
    position:;
    left:0;
    top:0;
    background:linear-gradient(180deg,#43B6C0,#003099);
    padding-top:20px;
    z-index:1000;
}

.sidebar .brand{
    display:flex;
    align-items:center;
    padding:0 20px 20px;
    border-bottom:1px solid rgba(255,255,255,.2);
}

.sidebar .brand img{
    height:40px;
    margin-right:10px;
}

.sidebar .brand span{
    color:#fff;
    font-size:18px;
    font-weight:600;
}

.sidebar .nav-link{
    color:#fff !important;
    padding:12px 20px;
    margin:4px 10px;
    border-radius:8px;
    font-size:14px;
    display:flex;
    align-items:center;
    transition:.2s;
}

.sidebar .nav-link i{
    margin-right:10px;
    font-size:16px;
}

.sidebar .nav-link:hover{
    background:rgba(255,255,255,.15);
}

.sidebar .nav-link.active{
    background:#fff;
    color:#003099 !important;
}

/* ===== LOGOUT ===== */
.sidebar .logout{
    position:absolute;
    bottom:20px;
    width:100%;
}

.sidebar .logout .nav-link{
    color:#fff !important;
    padding:12px 20px;
    margin:4px 10px;
    border-radius:8px;
    font-size:14px;
    display:flex;
    align-items:center;
    transition:.2s;
}

.sidebar .logout .nav-link i{
    margin-right:10px;
    font-size:16px;
}

.sidebar .logout .nav-link:hover{
    background:rgba(255,255,255,.2);
    color:#fff !important;
}


/* ===== CONTENT ===== */
.main-content{
    margin-left:260px;
    padding:32px 28px; /* atas-bawah | kiri-kanan */
}


/* ===== MOBILE ===== */
@media (max-width:768px){
    .sidebar{
        left:-260px;
        transition:.3s;
    }
    .sidebar.show{
        left:0;
    }
    .main-content{
        margin-left:0;
    }
}
</style>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <!-- BRAND -->
    <div class="brand">
        <img src="../assets/img/logo.png">
        <span>SCLibrary</span>
    </div>

    <!-- MENU -->
    <ul class="nav flex-column mt-3">

        <li class="nav-item">
            <a class="nav-link <?= active('dashboard.php') ?>" href="dashboard.php">
                <i class="bi bi-house-door"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= active('buku.php') ?>" href="buku.php">
                <i class="bi bi-book"></i> Buku
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= active('genre.php') ?>" href="genre.php">
                <i class="bi bi-tags"></i> Genre
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= active('type_buku.php') ?>" href="type_buku.php">
                <i class="bi bi-collection"></i> Type Buku
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= active('user.php') ?>" href="user.php">
                <i class="bi bi-people"></i> User
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= active('pinjaman.php') ?>" href="pinjaman.php">
                <i class="bi bi-journal-text"></i> Pinjaman
            </a>
        </li>

    </ul>

    <!-- LOGOUT -->
    >
<div class="logout">
    <a class="nav-link" href="../auth/logout.php"
       onclick="return confirm('Yakin ingin logout?')">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</div>

</div>
