<?php
// Koneksi PHP ke MySQL (XAMPP, port 3307)
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "ecommerce";
$port = 3307;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
// Jika sampai sini tanpa error, koneksi berhasil.
?> echo "Connected!"