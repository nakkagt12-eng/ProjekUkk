<?php
session_start();
require_once "../config/config.php";

$username = $_POST['username'];
$password = $_POST['password'];

 $stmt = $conn->prepare(
    "SELECT id, username, password, role
     FROM users
     WHERE username = ?"
);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

 if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

     if (password_verify($password, $user['password'])) {

         $_SESSION['id']       = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

         if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        exit;
    }
}

 header("Location: login.php");
exit;
