<?php
require 'koneksi.php';
$id = intval($_GET['id'] ?? 0);
$error = '';

// Ambil info mata kuliah
$stmt = $conn->prepare("SELECT * FROM mata_kuliah WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$mk = $stmt->get_result()->fetch_assoc();

if (!$mk) {
    header("Location: mata_kuliah.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token keamanan tidak valid.");
    }

    // Karena relasi menggunakan ON DELETE RESTRICT, tangkap error jika masih terikat relasi
    $stmt_del = $conn->prepare("DELETE FROM mata_kuliah WHERE id = ?");
    $stmt_del->bind_param("i", $id);
    
    try {
        if ($stmt_del->execute()) {
            header("Location: mata_kuliah.php?pesan=Mata kuliah berhasil dihapus");
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        $error = "Data belum tersimpan. Mata kuliah masih memiliki materi atau tugas. Hapus data terkait terlebih dahulu.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Hapus Mata Kuliah</h2>
    <?php if($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>
    <p>Apakah Anda yakin ingin menghapus mata kuliah: <strong><?= htmlspecialchars($mk['nama']) ?></strong>?</p>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="btn btn-danger">Ya, hapus data</button>
        <a href="mata_kuliah.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>