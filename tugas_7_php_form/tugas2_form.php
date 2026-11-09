<?php
// ===== TUGAS 2: Form Input Produk =====
$nama = $harga = $deskripsi = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama      = $_POST['nama_produk'];
    $harga     = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Form Tambah Produk</title></head>
<body>
  <h2>Tambah Produk Baru</h2>

  <form method="POST" action="">
    <p>Nama Produk:<br>
       <input type="text" name="nama_produk" value="<?php echo htmlspecialchars($nama); ?>"></p>
    <p>Harga:<br>
       <input type="number" name="harga" value="<?php echo htmlspecialchars($harga); ?>"></p>
    <p>Deskripsi:<br>
       <textarea name="deskripsi"><?php echo htmlspecialchars($deskripsi); ?></textarea></p>
    <button type="submit">Simpan</button>
  </form>

  <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <hr>
    <h3>Data yang diinput:</h3>
    <p>Nama      : <?php echo htmlspecialchars($nama); ?></p>
    <p>Harga     : <?php echo htmlspecialchars($harga); ?></p>
    <p>Deskripsi : <?php echo htmlspecialchars($deskripsi); ?></p>
  <?php endif; ?>
</body>
</html>