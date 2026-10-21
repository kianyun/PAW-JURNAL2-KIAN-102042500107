<?php
require 'koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = trim($_POST['kode']);
    $nama = trim($_POST['nama']);
    $dosen = trim($_POST['dosen']);
    $semester = intval($_POST['semester']);

    // Validasi aturan input
    if (!preg_match('/^[A-Z0-9\-]{3,20}$/', $kode)) {
        $error = "Kode harus 3-20 karakter berupa huruf kapital, angka, atau tanda -.";
    } elseif (strlen($nama) < 3 || strlen($nama) > 100) {
        $error = "Nama harus 3-100 karakter.";
    } elseif (strlen($dosen) < 3 || strlen($dosen) > 100) {
        $error = "Nama dosen harus 3-100 karakter.";
    } elseif ($semester < 1 || $semester > 14) {
        $error = "Semester harus berada di antara rentang 1 sampai 14.";
    } else {
        // Cek keunikan kode
        $stmt_check = $conn->prepare("SELECT id FROM mata_kuliah WHERE kode = ?");
        $stmt_check->bind_param("s", $kode);
        $stmt_check->execute();
        if ($stmt_check->get_result()->num_rows > 0) {
            $error = "Kode mata kuliah sudah digunakan.";
        } else {
            $stmt = $conn->prepare("INSERT INTO mata_kuliah (kode, nama, dosen, semester) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $kode, $nama, $dosen, $semester);
            if ($stmt->execute()) {
                header("Location: mata_kuliah.php?pesan=Mata kuliah berhasil ditambahkan");
                exit;
            } else {
                $error = "Gagal menyimpan data.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Tambah Mata Kuliah</h2>
    <?php if($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Kode Mata Kuliah</label>
            <input type="text" name="kode" value="<?= htmlspecialchars($_POST['kode'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Mata Kuliah</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Dosen Pengampu</label>
            <input type="text" name="dosen" value="<?= htmlspecialchars($_POST['dosen'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Semester (1-14)</label>
            <input type="text" name="semester" value="<?= htmlspecialchars($_POST['semester'] ?? '') ?>" required>
        </div>
        <button type="submit">Simpan data</button>
        <a href="mata_kuliah.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>