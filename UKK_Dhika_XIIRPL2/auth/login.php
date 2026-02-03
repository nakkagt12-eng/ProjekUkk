<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | SCLibrary</title>

     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #43B6C0, #003099);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-card {
            background: #fff;
            border-radius: 30px;
            padding: 60px;
            width: 100%;
            max-width: 1200px;
        }

        .form-card {
            background: #d9d9d9;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.28);
            max-width: 460px;
            position: relative;
        }

         .avatar {
            width: 110px;
            height: 110px;
            background: #ffffff;
            border-radius: 50%;
            margin: 0 auto 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,.25);
        }

        .avatar i {
            font-size: 52px;
            color: #003099;
        }

        .form-control {
            border-radius: 8px;
            margin-bottom: 22px;
            padding: 14px;
            font-size: 15px;
        }

        .btn-login {
            border-radius: 22px;
            padding: 8px 36px;
            font-size: 15px;
            float: right;
        }

         .logo img {
            width: 360px;
            max-width: 100%;
        }

        .small-text {
            font-size: 13px;
            margin-top: 22px;
        }

        @media (max-width: 768px) {
            .main-card {
                padding: 35px;
            }

            .logo img {
                width: 260px;
            }

            .form-card {
                margin: 35px auto 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="main-card">
    <div class="row align-items-center">

         <div class="col-md-6 text-center logo">
            <img src="../assets/img/hero-logo.png" alt="SCLibrary Logo">
            <p class="mt-2 text-muted">Smart Library Solution</p>

            <p class="small-text">
                if you don't have an account please
                <a href="register.php">Register</a>
            </p>
        </div>

         <div class="col-md-6">
            <div class="form-card">

                 <div class="avatar">
                    <i class="bi bi-person-fill"></i>
                </div>

                <form action="proses_login.php" method="POST">
                    <input type="text" name="username" class="form-control" placeholder="Username..." required>
                    <input type="password" name="password" class="form-control" placeholder="Password..." required>

                    <button type="submit" class="btn btn-light btn-login">
                        Login
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>

</body>
</html>
