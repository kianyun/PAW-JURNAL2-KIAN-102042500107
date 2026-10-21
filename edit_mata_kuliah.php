<?php
require 'koneksi.php';
$error = '';
$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM mata_kuliah WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$mk = $stmt->get_result()->fetch_assoc();

if (!$mk) {
    header("Location: mata_kuliah.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = trim($_POST['kode']);
    $nama = trim($_POST['nama']);
    $dosen = trim($_POST['dosen']);
    $semester = intval($_POST['semester']);

    if (!preg_match('/^[A-Z0-9\-]{3,20}$/', $kode)) {
        $error = "Format kode tidak valid.";
    } elseif (strlen($nama) < 3 || strlen($nama) > 100) {
        $error = "Nama harus 3-100 karakter.";
    } elseif ($semester < 1 || $semester > 14) {
        $error = "Semester tidak valid.";
    } else {
        // Cek duplikasi kode selain ID saat ini
        $stmt_chk = $conn->prepare("SELECT id FROM mata_kuliah WHERE kode = ? AND id != ?");
        $stmt_chk->bind_param("si", $kode, $id);
        $stmt_chk->execute();
        if ($stmt_chk->get_result()->num_rows > 0) {
            $error = "Kode mata kuliah sudah digunakan.";
        } else {
            // Cek apakah ada perubahan data
            if ($mk['kode'] === $kode && $mk['nama'] === $nama && $mk['dosen'] === $dosen && $mk['semester'] == $semester) {
                $error = "Tidak ada perubahan data yang disimpan.";
            } else {
                $stmt_upd = $conn->prepare("UPDATE mata_kuliah SET kode = ?, nama = ?, dosen = ?, semester = ? WHERE id = ?");
                $stmt_upd->bind_param("sssii", $kode, $nama, $dosen, $semester, $id);
                $stmt_upd->execute();
                header("Location: mata_kuliah.php?pesan=Mata kuliah berhasil diperbarui");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Edit Mata Kuliah</h2>
    <?php if($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Kode Mata Kuliah</label>
            <input type="text" name="kode" value="<?= htmlspecialchars($mk['kode']) ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Mata Kuliah</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($mk['nama']) ?>" required>
        </div>
        <div class="form-group">
            <label>Dosen Pengampu</label>
            <input type="text" name="dosen" value="<?= htmlspecialchars($mk['dosen']) ?>" required>
        </div>
        <div class="form-group">
            <label>Semester</label>
            <input type="text" name="semester" value="<?= htmlspecialchars($mk['semester']) ?>" required>
        </div>
        <button type="submit">Simpan data</button>
        <a href="mata_kuliah.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>