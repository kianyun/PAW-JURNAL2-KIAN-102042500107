<?php
$host = 'localhost';
$user = 'root';
$pass = 'air123456!';
$db   = 'db_lms_paw';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Token CSRF sederhana untuk pengamanan form hapus/POST
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>