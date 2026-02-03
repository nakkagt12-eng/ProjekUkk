<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    exit("Akses ditolak");
}

$keyword  = $_GET['search'] ?? '';
$type     = $_GET['type'] ?? '';
$bulan    = $_GET['bulan'];
$filename = $_GET['filename'] ?? 'data_pinjaman';

$like  = "%$keyword%";
$year  = date('Y', strtotime($bulan));
$month = date('m', strtotime($bulan));

$sql = "
    SELECT 
        p.username,
        b.nama_buku,
        t.nama_type AS kategori,
        p.tgl_pinjam,
        p.tgl_kembali,
        p.tgl_dikembalikan,
        p.status
    FROM pinjam p
    JOIN buku b ON p.id_buku = b.id_buku
    JOIN type_buku t ON b.id_type = t.id_type
    WHERE (p.username LIKE ? OR b.nama_buku LIKE ?)
      AND MONTH(p.tgl_pinjam) = ?
      AND YEAR(p.tgl_pinjam) = ?
";

if (!empty($type)) {
    $sql .= " AND t.id_type = ?";
}

$stmt = $conn->prepare($sql);

if (!empty($type)) {
    $stmt->bind_param("ssiii", $like, $like, $month, $year, $type);
} else {
    $stmt->bind_param("ssii", $like, $like, $month, $year);
}

$stmt->execute();
$result = $stmt->get_result();

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename={$filename}.xls");

echo "<table border='1'>
<tr>
<th>No</th><th>Username</th><th>Buku</th><th>Kategori</th>
<th>Tgl Pinjam</th><th>Rencana Kembali</th>
<th>Dikembalikan</th><th>Status</th>
</tr>";

$no=1;
while($row=$result->fetch_assoc()){
    echo "<tr>
    <td>{$no}</td>
    <td>{$row['username']}</td>
    <td>{$row['nama_buku']}</td>
    <td>{$row['kategori']}</td>
    <td>{$row['tgl_pinjam']}</td>
    <td>{$row['tgl_kembali']}</td>
    <td>".($row['tgl_dikembalikan'] ?? 'Belum')."</td>
    <td>{$row['status']}</td>
    </tr>";
    $no++;
}
echo "</table>";
exit;
