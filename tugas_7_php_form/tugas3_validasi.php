<?php
// ===== TUGAS 3: Validasi + Simpan ke Database =====
require 'koneksi.php';

$errors = [];
$sukses = "";
$nama = $harga = $deskripsi = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // trim() membuang spasi di awal/akhir agar " " tetap dianggap kosong
    $nama      = trim($_POST['nama_produk']);
    $harga     = trim($_POST['harga']);
    $deskripsi = trim($_POST['deskripsi']);

    // --- Validasi: tidak boleh kosong ---
    if ($nama == "")      { $errors[] = "Nama produk wajib diisi."; }
    if ($harga == "")     { $errors[] = "Harga wajib diisi."; }
    if ($deskripsi == "") { $errors[] = "Deskripsi wajib diisi."; }

    // --- Jika lolos validasi, simpan ke DB ---
    if (empty($errors)) {
        $stok = 0;
        // prepared statement = aman dari SQL injection
        $stmt = $conn->prepare(
            "INSERT INTO products (nama_produk, harga, deskripsi, stok) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("sisi", $nama, $harga, $deskripsi, $stok);

        if ($stmt->execute()) {
            $sukses = "Produk berhasil disimpan! (ID baru: " . $stmt->insert_id . ")";
            $nama = $harga = $deskripsi = "";   // kosongkan form setelah sukses
        } else {
            $errors[] = "Gagal menyimpan: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Tambah Produk + Validasi</title></head>
<body>
  <h2>Tambah Produk Baru</h2>

  <?php if ($sukses): ?>
    <p style="color:green;"><?php echo $sukses; ?></p>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <ul style="color:red;">
      <?php foreach ($errors as $e): ?>
        <li><?php echo $e; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="POST" action="">
    <p>Nama Produk:<br>
       <input type="text" name="nama_produk" value="<?php echo htmlspecialchars($nama); ?>"></p>
    <p>Harga:<br>
       <input type="number" name="harga" value="<?php echo htmlspecialchars($harga); ?>"></p>
    <p>Deskripsi:<br>
       <textarea name="deskripsi"><?php echo htmlspecialchars($deskripsi); ?></textarea></p>
    <button type="submit">Simpan</button>
  </form>
</body>
</html>