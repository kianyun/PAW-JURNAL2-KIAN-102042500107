<?php
require 'koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mata_kuliah_id = intval($_POST['mata_kuliah_id']);
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $tautan = trim($_POST['tautan']);

    if ($mata_kuliah_id <= 0) {
        $error = "Pilih mata kuliah yang valid.";
    } elseif (strlen($judul) < 3 || strlen($judul) > 150) {
        $error = "Judul harus 3-150 karakter.";
    } elseif (strlen($isi) < 3 || strlen($isi) > 2000) {
        $error = "Isi materi harus 3-2000 karakter.";
    } elseif (!filter_var($tautan, FILTER_VALIDATE_URL) || (strpos($tautan, 'http://') !== 0 && strpos($tautan, 'https://') !== 0)) {
        $error = "Tautan materi tidak valid. Gunakan tautan yang diawali http:// atau https://.";
    } else {
        $stmt = $conn->prepare("INSERT INTO materi (mata_kuliah_id, judul, isi, tautan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $mata_kuliah_id, $judul, $isi, $tautan);
        if ($stmt->execute()) {
            header("Location: materi.php?pesan=Materi berhasil ditambahkan");
            exit;
        } else {
            $error = "Gagal menyimpan data.";
        }
    }
}

$list_mk = $conn->query("SELECT id, nama FROM mata_kuliah ORDER BY nama");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Materi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Tambah Materi</h2>
    <?php if($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Mata Kuliah</label>
            <select name="mata_kuliah_id" required>
                <option value="">Pilih Mata kuliah</option>
                <?php while($m = $list_mk->fetch_assoc()): ?>
                    <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nama']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Judul materi</label>
            <input type="text" name="judul" value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Isi materi</label>
            <textarea name="isi" rows="5" required><?= htmlspecialchars($_POST['isi'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Tautan referensi</label>
            <input type="text" name="tautan" value="<?= htmlspecialchars($_POST['tautan'] ?? '') ?>" required>
        </div>
        <button type="submit">Simpan data</button>
        <a href="materi.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>