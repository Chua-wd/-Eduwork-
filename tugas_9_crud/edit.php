<?php require 'koneksi.php';

$id = $_GET['id'] ?? ($_POST['id'] ?? 0);

// Simpan perubahan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama      = trim($_POST['nama_produk']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga     = trim($_POST['harga']);
    $kategori  = trim($_POST['kategori']);
    $stok      = trim($_POST['stok']);

    // Jika upload gambar baru, ganti; kalau tidak, pakai yang lama
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $namaGambar = time() . '_' . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/' . $namaGambar);
        $stmt = $conn->prepare(
            "UPDATE products SET nama_produk=?, deskripsi=?, gambar=?, kategori=?, harga=?, stok=? WHERE id=?"
        );
        $stmt->bind_param("ssssiii", $nama, $deskripsi, $namaGambar, $kategori, $harga, $stok, $id);
    } else {
        $stmt = $conn->prepare(
            "UPDATE products SET nama_produk=?, deskripsi=?, kategori=?, harga=?, stok=? WHERE id=?"
        );
        $stmt->bind_param("sssiii", $nama, $deskripsi, $kategori, $harga, $stok, $id);
    }
    $stmt->execute();
    header("Location: dashboard.php");
    exit;
}

// Ambil data lama untuk ditampilkan di form
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row) { die("Produk tidak ditemukan."); }
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Edit Produk</title></head>
<body>
  <h1>Edit Produk</h1>
  <form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    <p>Nama Produk:<br>
       <input type="text" name="nama_produk" value="<?= htmlspecialchars($row['nama_produk']) ?>" required></p>
    <p>Deskripsi:<br>
       <textarea name="deskripsi"><?= htmlspecialchars($row['deskripsi']) ?></textarea></p>
    <p>Kategori:<br>
       <input type="text" name="kategori" value="<?= htmlspecialchars($row['kategori']) ?>"></p>
    <p>Harga:<br>
       <input type="number" name="harga" value="<?= $row['harga'] ?>" required></p>
    <p>Stok:<br>
       <input type="number" name="stok" value="<?= $row['stok'] ?>"></p>
    <p>Gambar sekarang:<br>
       <?php if ($row['gambar']): ?>
         <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" width="80"><br>
       <?php endif; ?>
       Ganti (opsional): <input type="file" name="gambar" accept="image/*"></p>
    <button type="submit">Update</button>
    <a href="dashboard.php">Batal</a>
  </form>
</body>
</html>