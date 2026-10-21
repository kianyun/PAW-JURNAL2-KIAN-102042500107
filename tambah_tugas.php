<?php
require 'koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mata_kuliah_id = intval($_POST['mata_kuliah_id']);
    $judul = trim($_POST['judul']);
    $instruksi = trim($_POST['instruksi']);
    $tenggat = trim($_POST['tenggat']);
    $status = trim($_POST['status']);

    // Validasi status hanya boleh Draf, Aktif, atau Selesai
    $valid_status = ['Draf', 'Aktif', 'Selesai'];

    if ($mata_kuliah_id <= 0) {
        $error = "Pilih mata kuliah yang valid.";
    } elseif (strlen($judul) < 3 || strlen($judul) > 150) {
        $error = "Judul harus 3-150 karakter.";
    } elseif (strlen($instruksi) < 3 || strlen($instruksi) > 2000) {
        $error = "Instruksi harus 3-2000 karakter.";
    } elseif (!DateTime::createFromFormat('Y-m-d', $tenggat)) {
        $error = "Format tanggal tenggat tidak valid.";
    } elseif (!in_array($status, $valid_status)) {
        $error = "Status tugas tidak valid.";
    } else {
        $stmt = $conn->prepare("INSERT INTO tugas (mata_kuliah_id, judul, instruksi, tenggat, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $mata_kuliah_id, $judul, $instruksi, $tenggat, $status);
        if ($stmt->execute()) {
            header("Location: tugas.php?pesan=Tugas berhasil ditambahkan");
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
    <title>Tambah Tugas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Tambah Tugas</h2>
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
            <label>Judul tugas</label>
            <input type="text" name="judul" value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Instruksi tugas</label>
            <textarea name="instruksi" rows="5" required><?= htmlspecialchars($_POST['instruksi'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Tenggat (YYYY-MM-DD)</label>
            <input type="date" name="tenggat" value="<?= htmlspecialchars($_POST['tenggat'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" required>
                <option value="Draf">Draf</option>
                <option value="Aktif">Aktif</option>
                <option value="Selesai">Selesai</option>
            </select>
        </div>
        <button type="submit">Simpan data</button>
        <a href="tugas.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>