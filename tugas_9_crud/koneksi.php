<?php
$conn = new mysqli("127.0.0.1", "root", "", "ecommerce", 3307);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>