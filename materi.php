<?php
require 'koneksi.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_mk = isset($_GET['mata_kuliah_id']) ? intval($_GET['mata_kuliah_id']) : 0;

$query = "SELECT materi.*, mata_kuliah.nama as nama_mk FROM materi JOIN mata_kuliah ON materi.mata_kuliah_id = mata_kuliah.id WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $query .= " AND materi.judul LIKE ?";
    $params[] = "%" . $search . "%";
    $types .= "s";
}

if ($filter_mk > 0) {
    $query .= " AND materi.mata_kuliah_id = ?";
    $params[] = $filter_mk;
    $types .= "i";
}

$query .= " ORDER BY materi.judul ASC";
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$list_mk = $conn->query("SELECT id, nama FROM mata_kuliah ORDER BY nama");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Materi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Materi Pembelajaran</h2>
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

    <a href="tambah_materi.php" class="btn">Tambah materi</a>

    <form method="GET" style="margin-top: 15px;">
        <select name="mata_kuliah_id" style="width: 200px; display:inline-block;">
            <option value="0">Semua mata kuliah</option>
            <?php while($m = $list_mk->fetch_assoc()): ?>
                <option value="<?= $m['id'] ?>" <?= $filter_mk == $m['id'] ? 'selected' : '' ?>><?= htmlspecialchars($m['nama']) ?></option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="search" placeholder="Cari judul materi..." value="<?= htmlspecialchars($search) ?>" style="width: 200px; display:inline-block;">
        <button type="submit">Cari</button>
        <a href="materi.php" class="btn btn-secondary">Reset</a>
    </form>

    <p style="margin-top:15px;">Menampilkan <?= $result->num_rows ?> data.</p>

    <table>
        <thead>
            <tr>
                <th>Judul Materi</th>
                <th>Mata Kuliah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                    <td>
                        <a href="detail_materi.php?id=<?= $row['id'] ?>" class="btn">Detail</a>
                        <a href="edit_materi.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
                        <a href="hapus_materi.php?id=<?= $row['id'] ?>" class="btn btn-danger">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3">Data tidak ditemukan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>