<?php require 'koneksi.php';
$result = $conn->query("SELECT * FROM products ORDER BY id");
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Dashboard Produk</title></head>
<body>
  <h1>Dashboard Produk</h1>
  <a href="tambah.php"><button>+ Tambah Produk</button></a>

  <table border="1" cellpadding="8" style="margin-top:15px;">
    <tr>
      <th>ID</th><th>Nama</th><th>Deskripsi</th><th>Gambar</th>
      <th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
        <td>
          <?php if (!empty($row['gambar'])): ?>
            <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" width="60">
          <?php else: ?> - <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row['kategori']) ?></td>
        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
        <td><?= $row['stok'] ?></td>
        <td>
          <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
          <a href="hapus.php?id=<?= $row['id'] ?>"
             onclick="return confirm('Yakin hapus produk ini?')">Hapus</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>