<?php
require 'koneksi.php';
$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM tugas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$tugas = $stmt->get_result()->fetch_assoc();

if (!$tugas) {
    header("Location: tugas.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token keamanan tidak valid.");
    }
    $stmt_del = $conn->prepare("DELETE FROM tugas WHERE id = ?");
    $stmt_del->bind_param("i", $id);
    $stmt_del->execute();
    header("Location: tugas.php?pesan=Tugas berhasil dihapus");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Tugas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Hapus Tugas</h2>
    <p>Apakah Anda yakin ingin menghapus tugas: <strong><?= htmlspecialchars($tugas['judul']) ?></strong>?</p>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="btn btn-danger">Ya, hapus data</button>
        <a href="tugas.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>