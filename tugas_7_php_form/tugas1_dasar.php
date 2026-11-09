<?php
// ===== TUGAS 1: Dasar PHP =====

// 1) Deklarasi variabel
$nama_produk = "Keyboard Mechanical";
$harga       = 450000;
$stok        = 25;

// 2) Operator (aritmatika)
$harga_diskon = $harga - ($harga * 0.1);  // diskon 10%
$total_nilai  = $harga * $stok;           // nilai total stok

echo "Nama Produk  : $nama_produk <br>";
echo "Harga        : Rp " . number_format($harga, 0, ',', '.') . "<br>";
echo "Harga Diskon : Rp " . number_format($harga_diskon, 0, ',', '.') . "<br>";
echo "Total Stok   : Rp " . number_format($total_nilai, 0, ',', '.') . "<br>";
echo "<hr>";

// 3) Percabangan if-else
if ($stok > 0) {
    echo "Status: Produk TERSEDIA (sisa stok $stok)";
} else {
    echo "Status: Produk HABIS";
}
?>