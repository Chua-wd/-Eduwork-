<?php require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama      = trim($_POST['nama_produk']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga     = trim($_POST['harga']);
    $kategori  = trim($_POST['kategori']);
    $stok      = trim($_POST['stok']);

    // --- Upload gambar ---
    $namaGambar = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $namaGambar = time() . '_' . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/' . $namaGambar);
    }

    $stmt = $conn->prepare(
        "INSERT INTO products (nama_produk, deskripsi, gambar, kategori, harga, stok)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssii", $nama, $deskripsi, $namaGambar, $kategori, $harga, $stok);
    $stmt->execute();

    header("Location: dashboard.php");   // kembali ke daftar
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Tambah Produk</title></head>
<body>
  <h1>Tambah Produk</h1>
  <!-- enctype WAJIB untuk upload file -->
  <form method="POST" enctype="multipart/form-data">
    <p>Nama Produk:<br><input type="text" name="nama_produk" required></p>
    <p>Deskripsi:<br><textarea name="deskripsi"></textarea></p>
    <p>Kategori:<br><input type="text" name="kategori" value="Umum"></p>
    <p>Harga:<br><input type="number" name="harga" required></p>
    <p>Stok:<br><input type="number" name="stok" value="0"></p>
    <p>Gambar:<br><input type="file" name="gambar" accept="image/*"></p>
    <button type="submit">Simpan</button>
    <a href="dashboard.php">Batal</a>
  </form>
</body>
</html>