<?php
session_start();
include "koneksi.php";

// Ambil kode produk dari URL
if (!isset($_GET['kode']) || $_GET['kode'] == '') {
    die("Kode produk tidak ada.");
}

$kode = $_GET['kode'];

// Ambil jumlah
$jumlah = isset($_GET['jumlah']) ? (int)$_GET['jumlah'] : 1;

if ($jumlah < 1) {
    $jumlah = 1;
}

// Cari produk
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM produk WHERE kode_produk = ? LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "s", $kode);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Produk tidak ditemukan.");
}


// Buat keranjang
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}


// Masukkan produk ke keranjang
if (isset($_SESSION['keranjang'][$kode])) {

    $_SESSION['keranjang'][$kode] += $jumlah;

} else {

    $_SESSION['keranjang'][$kode] = $jumlah;

}


// Pergi ke keranjang
header("Location: keranjang.php");
exit;
?>