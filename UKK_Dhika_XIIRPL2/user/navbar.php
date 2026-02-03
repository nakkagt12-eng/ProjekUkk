<nav class="navbar navbar-expand-lg navbar-dark sticky-top sc-navbar">
    <div class="container">
        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <img src="../assets/img/logo.png" alt="SCLibrary">
            <span class="ms-2 fw-semibold">SCLibrary</span>
        </a>

        <!-- TOGGLER -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>"
                       href="dashboard.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='list.php'?'active':'' ?>"
                       href="list.php">
                        Buku
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='history.php'?'active':'' ?>"
                       href="history.php">
                        History
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link logout-btn" href="../auth/logout.php" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
