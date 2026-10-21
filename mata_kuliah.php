<?php
require 'koneksi.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM mata_kuliah WHERE kode LIKE ? OR nama LIKE ? ORDER BY nama");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM mata_kuliah ORDER BY nama");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Mata Kuliah</h2>
    <nav>
        <a href="index.php">Beranda</a>
        <a href="mata_kuliah.php">Mata Kuliah</a>
        <a href="materi.php">Materi</a>
        <a href="tugas.php">Tugas</a>
    </nav>
    <hr>

    <?php if(isset($_GET['pesan'])): ?>
        <div class="alert-success"><?= htmlspecialchars($_GET['pesan']) ?></div>
    <?php endif; ?>

    <a href="tambah_mata_kuliah.php" class="btn">Tambah Mata Kuliah</a>
    
    <form method="GET" style="margin-top: 15px;">
        <input type="text" name="search" placeholder="Cari kode atau nama..." value="<?= htmlspecialchars($search) ?>" style="width: 250px; display:inline-block;">
        <button type="submit">Cari</button>
        <a href="mata_kuliah.php" class="btn btn-secondary">Reset</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>Kode & Nama</th>
                <th>Dosen / Semester</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['kode'] . ' - ' . $row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['dosen'] . ' / Semester ' . $row['semester']) ?></td>
                    <td>
                        <a href="edit_mata_kuliah.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
                        <a href="hapus_mata_kuliah.php?id=<?= $row['id'] ?>" class="btn btn-danger">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3">Tidak ada data ditemukan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>