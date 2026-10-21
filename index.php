<?php
require 'koneksi.php';

// Menghitung jumlah data menggunakan COUNT
$q_mk = $conn->query("SELECT COUNT(*) as total FROM mata_kuliah");
$jml_mk = $q_mk->fetch_assoc()['total'];

$q_mat = $conn->query("SELECT COUNT(*) as total FROM materi");
$jml_mat = $q_mat->fetch_assoc()['total'];

$q_tug = $conn->query("SELECT COUNT(*) as total FROM tugas");
$jml_tug = $q_tug->fetch_assoc()['total'];

// Mengambil daftar mata kuliah untuk ringkasan beranda
$list_mk = $conn->query("SELECT kode, nama FROM mata_kuliah ORDER BY nama");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - Learning Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Learning Management System</h1>
    <p>Pengelolaan mata kuliah, materi pembelajaran, dan tugas.</p>
    
    <nav>
        <a href="index.php">Beranda</a>
        <a href="mata_kuliah.php">Mata Kuliah (<?= $jml_mk ?>)</a>
        <a href="materi.php">Materi (<?= $jml_mat ?>)</a>
        <a href="tugas.php">Tugas (<?= $jml_tug ?>)</a>
    </nav>
    <hr>

    <h3>Ringkasan Sistem</h3>
    <ul>
        <li>Total Mata Kuliah: <strong><?= $jml_mk ?></strong></li>
        <li>Total Materi: <strong><?= $jml_mat ?></strong></li>
        <li>Total Tugas: <strong><?= $jml_tug ?></strong></li>
    </ul>

    <h3>Mata Kuliah Tersedia</h3>
    <ul>
        <?php while($row = $list_mk->fetch_assoc()): ?>
            <li><?= htmlspecialchars($row['kode'] . ' - ' . $row['nama']) ?></li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>