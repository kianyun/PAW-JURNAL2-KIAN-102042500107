<?php
require 'koneksi.php';
$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT tugas.*, mata_kuliah.nama as nama_mk FROM tugas JOIN mata_kuliah ON tugas.mata_kuliah_id = mata_kuliah.id WHERE tugas.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$tugas = $stmt->get_result()->fetch_assoc();

if (!$tugas) {
    header("Location: tugas.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Tugas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Detail Tugas</h2>
    <table>
        <tr>
            <th>Mata kuliah</th>
            <td><?= htmlspecialchars($tugas['nama_mk']) ?></td>
        </tr>
        <tr>
            <th>Judul tugas</th>
            <td><?= htmlspecialchars($tugas['judul']) ?></td>
        </tr>
        <tr>
            <th>Instruksi</th>
            <td><?= nl2br(htmlspecialchars($tugas['instruksi'])) ?></td>
        </tr>
        <tr>
            <th>Tenggat</th>
            <td><?= htmlspecialchars($tugas['tenggat']) ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?= htmlspecialchars($tugas['status']) ?></td>
        </tr>
    </table>
    <br>
    <a href="edit_tugas.php?id=<?= $tugas['id'] ?>" class="btn">Edit data</a>
    <a href="tugas.php" class="btn btn-secondary">Kembali</a>
</div>
</body>
</html>