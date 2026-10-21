<?php
require 'koneksi.php';
$id = intval($_GET['id'] ?? 0);
$error = '';

$stmt = $conn->prepare("SELECT * FROM materi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$materi = $stmt->get_result()->fetch_assoc();

if (!$materi) {
    header("Location: materi.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mata_kuliah_id = intval($_POST['mata_kuliah_id']);
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $tautan = trim($_POST['tautan']);

    if (strlen($judul) < 3 || strlen($judul) > 150) {
        $error = "Judul tidak valid.";
    } elseif (!filter_var($tautan, FILTER_VALIDATE_URL) || (strpos($tautan, 'http://') !== 0 && strpos($tautan, 'https://') !== 0)) {
        $error = "Tautan harus diawali http:// atau https://.";
    } else {
        if ($materi['mata_kuliah_id'] == $mata_kuliah_id && $materi['judul'] === $judul && $materi['isi'] === $isi && $materi['tautan'] === $tautan) {
            $error = "Tidak ada perubahan data yang disimpan.";
        } else {
            $stmt_upd = $conn->prepare("UPDATE materi SET mata_kuliah_id = ?, judul = ?, isi = ?, tautan = ? WHERE id = ?");
            $stmt_upd->bind_param("isssi", $mata_kuliah_id, $judul, $isi, $tautan, $id);
            $stmt_upd->execute();
            header("Location: materi.php?pesan=Materi berhasil diperbarui");
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
    <title>Edit Materi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Edit Materi</h2>
    <?php if($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Mata Kuliah</label>
            <select name="mata_kuliah_id" required>
                <?php while($m = $list_mk->fetch_assoc()): ?>
                    <option value="<?= $m['id'] ?>" <?= $materi['mata_kuliah_id'] == $m['id'] ? 'selected' : '' ?>><?= htmlspecialchars($m['nama']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Judul materi</label>
            <input type="text" name="judul" value="<?= htmlspecialchars($materi['judul']) ?>" required>
        </div>
        <div class="form-group">
            <label>Isi materi</label>
            <textarea name="isi" rows="5" required><?= htmlspecialchars($materi['isi']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Tautan referensi</label>
            <input type="text" name="tautan" value="<?= htmlspecialchars($materi['tautan']) ?>" required>
        </div>
        <button type="submit">Simpan data</button>
        <a href="materi.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>