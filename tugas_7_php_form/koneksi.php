<?php
// ===== Koneksi ke MySQL (XAMPP, port 3307) =====
$host = "127.0.0.1";
$user = "root";
$pass = "";            // XAMPP default: kosong
$db   = "ecommerce";
$port = 3307;          // PENTING: MySQL kamu di 3307, bukan 3306

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>