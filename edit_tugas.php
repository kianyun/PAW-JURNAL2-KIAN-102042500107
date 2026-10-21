<?php
require 'koneksi.php';
$id = intval($_GET['id'] ?? 0);
$error = '';

$stmt = $conn->prepare("SELECT * FROM tugas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$tugas = $stmt->get_result()->fetch_assoc();

if (!$tugas) {
    header("Location: tugas.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mata_kuliah_id = intval($_POST['mata_kuliah_id']);
    $judul = trim($_POST['judul']);
    $instruksi = trim($_POST['instruksi']);
    $tenggat = trim($_POST['tenggat']);
    $status = trim($_POST['status']);

    if (strlen($judul) < 3 || strlen($judul) > 150) {
        $error = "Judul tidak valid.";
    } else {
        if ($tugas['mata_kuliah_id'] == $mata_kuliah_id && $tugas['judul'] === $judul && $tugas['instruksi'] === $instruksi && $tugas['tenggat'] === $tenggat && $tugas['status'] === $status) {
            $error = "Tidak ada perubahan data yang disimpan.";
        } else {
            $stmt_upd = $conn->prepare("UPDATE tugas SET mata_kuliah_id = ?, judul = ?, instruksi = ?, tenggat = ?, status = ? WHERE id = ?");
            $stmt_upd->bind_param("issssi", $mata_kuliah_id, $judul, $instruksi, $tenggat, $status, $id);
            $stmt_upd->execute();
            header("Location: tugas.php?pesan=Tugas berhasil diperbarui");
            exit;
        }
    }
}

$list_mk = $conn->query("SELECT id, nama FROM mata_kuliah ORDER BY nama");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Edit Tugas</h2>
    <?php if($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Mata Kuliah</label>
            <select name="mata_kuliah_id" required>
                <?php while($m = $list_mk->fetch_assoc()): ?>
                    <option value="<?= $m['id'] ?>" <?= $tugas['mata_kuliah_id'] == $m['id'] ? 'selected' : '' ?>><?= htmlspecialchars($m['nama']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Judul tugas</label>
            <input type="text" name="judul" value="<?= htmlspecialchars($tugas['judul']) ?>" required>
        </div>
        <div class="form-group">
            <label>Instruksi tugas</label>
            <textarea name="instruksi" rows="5" required><?= htmlspecialchars($tugas['instruksi']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Tenggat</label>
            <input type="date" name="tenggat" value="<?= htmlspecialchars($tugas['tenggat']) ?>" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" required>
                <option value="Draf" <?= $tugas['status'] === 'Draf' ? 'selected' : '' ?>>Draf</option>
                <option value="Aktif" <?= $tugas['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="Selesai" <?= $tugas['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>
        </div>
        <button type="submit">Simpan data</button>
        <a href="tugas.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>