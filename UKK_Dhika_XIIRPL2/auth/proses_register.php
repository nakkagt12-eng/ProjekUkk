<?php
require_once "../config/config.php";

$username = $_POST['username'];
$email    = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role     = "user";

$stmt = $conn->prepare(
    "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssss", $username, $password, $email, $role);

if ($stmt->execute()) {
    header("Location: login.php?success=1");
} else {
    echo "Register gagal: " . $stmt->error;
}
