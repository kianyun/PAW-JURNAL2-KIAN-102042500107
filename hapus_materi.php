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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token keamanan tidak valid.");
    }
    $stmt_del = $conn->prepare("DELETE FROM materi WHERE id = ?");
    $stmt_del->bind_param("i", $id);
    $stmt_del->execute();
    header("Location: materi.php?pesan=Materi berhasil dihapus");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Materi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Hapus Materi</h2>
    <p>Apakah Anda yakin ingin menghapus materi: <strong><?= htmlspecialchars($materi['judul']) ?></strong>?</p>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="btn btn-danger">Ya, hapus data</button>
        <a href="materi.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>