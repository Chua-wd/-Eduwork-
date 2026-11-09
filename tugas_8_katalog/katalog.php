<?php
require 'koneksi.php';

// Ambil pilihan kategori dari URL (?kategori=...)
$filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

if ($filter != '') {
    // Query dengan filter -> prepared statement (aman)
    $stmt = $conn->prepare("SELECT * FROM products WHERE kategori = ? ORDER BY id");
    $stmt->bind_param("s", $filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Tanpa filter -> tampilkan semua
    $result = $conn->query("SELECT * FROM products ORDER BY id");
}

// Daftar kategori unik untuk isi dropdown
$kategoriList = $conn->query("SELECT DISTINCT kategori FROM products ORDER BY kategori");
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Katalog Produk</title></head>
<body>
  <h1>Katalog Produk</h1>

  <!-- Form filter kategori -->
  <form method="GET" action="">
    <label>Filter Kategori: </label>
    <select name="kategori" onchange="this.form.submit()">
      <option value="">-- Semua Kategori --</option>
      <?php while ($k = $kategoriList->fetch_assoc()): ?>
        <option value="<?= htmlspecialchars($k['kategori']) ?>"
          <?= ($filter == $k['kategori']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($k['kategori']) ?>
        </option>
      <?php endwhile; ?>
    </select>
    <button type="submit">Tampilkan</button>
  </form>

  <hr>

  <!-- Tampilkan produk pakai looping -->
  <table border="1" cellpadding="8">
    <tr><th>ID</th><th>Nama</th><th>Harga</th><th>Kategori</th><th>Stok</th></tr>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['nama_produk']) ?></td>
          <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
          <td><?= htmlspecialchars($row['kategori']) ?></td>
          <td><?= $row['stok'] ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="5">Tidak ada produk pada kategori ini.</td></tr>
    <?php endif; ?>
  </table>
</body>
</html>