<?php
require 'koneksi.php';
$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT materi.*, mata_kuliah.nama as nama_mk FROM materi JOIN mata_kuliah ON materi.mata_kuliah_id = mata_kuliah.id WHERE materi.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$materi = $stmt->get_result()->fetch_assoc();

if (!$materi) {
    header("Location: materi.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Materi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Detail Materi</h2>
    <table>
        <tr>
            <th>Mata kuliah</th>
            <td><?= htmlspecialchars($materi['nama_mk']) ?></td>
        </tr>
        <tr>
            <th>Judul materi</th>
            <td><?= htmlspecialchars($materi['judul']) ?></td>
        </tr>
        <tr>
            <th>Isi materi</th>
            <td><?= nl2br(htmlspecialchars($materi['isi'])) ?></td>
        </tr>
        <tr>
            <th>Tautan referensi</th>
            <td><a href="<?= htmlspecialchars($materi['tautan']) ?>" target="_blank"><?= htmlspecialchars($materi['tautan']) ?></a></td>
        </tr>
    </table>
    <br>
    <a href="edit_materi.php?id=<?= $materi['id'] ?>" class="btn">Edit data</a>
    <a href="materi.php" class="btn btn-secondary">Kembali</a>
</div>
</body>
</html>