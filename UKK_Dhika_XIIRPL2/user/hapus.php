<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['username'])) exit;

$id = intval($_GET['id']);
$buku = intval($_GET['buku']);
$username = $_SESSION['username'];

$conn->query("
    DELETE FROM review 
    WHERE id_review='$id' 
    AND username='$username'
");

header("Location: viewall.php?id=$buku");
