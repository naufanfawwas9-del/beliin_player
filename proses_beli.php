<?php

session_start();

include "koneksi.php";


// ===============================
// CEK KERANJANG
// ===============================

$keranjang = $_SESSION['keranjang'] ?? [];

if (empty($keranjang)) {

    header("Location: keranjang.php");
    exit;

}


// ===============================
// AMBIL DATA PEMBELI
// ===============================

$nama = trim($_POST['nama'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$pembayaran = trim($_POST['pembayaran'] ?? '');


if (
    $nama == '' ||
    $no_hp == '' ||
    $alamat == '' ||
    $pembayaran == ''
) {

    die("Data pembeli belum lengkap.");

}


// ===============================
// HITUNG TOTAL
// ===============================

$total = 0;


foreach ($keranjang as $kode => $jumlah) {

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT harga FROM produk WHERE kode_produk = ? LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $kode
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);


    if (mysqli_num_rows($result) == 0) {
        continue;
    }


    $produk = mysqli_fetch_assoc($result);

    $total += $produk['harga'] * $jumlah;

}


// ===============================
// SIMPAN PESANAN
// ===============================

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO pesanan
    (nama, no_hp, alamat, pembayaran, total, status)
    VALUES (?, ?, ?, ?, ?, 'Menunggu')"
);


mysqli_stmt_bind_param(
    $stmt,
    "ssssi",
    $nama,
    $no_hp,
    $alamat,
    $pembayaran,
    $total
);


if (!mysqli_stmt_execute($stmt)) {

    die("Pesanan gagal disimpan: " . mysqli_error($koneksi));

}


// Ambil ID pesanan
$id_pesanan = mysqli_insert_id($koneksi);


// ===============================
// KOSONGKAN KERANJANG
// ===============================

$_SESSION['keranjang'] = [];


// ===============================
// HALAMAN BERHASIL
// ===============================

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Pesanan Berhasil - Beliin.com</title>

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background: #f6f6f6;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 15px;
}

.box {
    background: white;
    padding: 30px 20px;
    border-radius: 15px;
    text-align: center;
}

.icon {
    font-size: 70px;
    margin-bottom: 15px;
}

h1 {
    color: #ff5a00;
    margin-bottom: 10px;
}

.info {
    margin-top: 20px;
    line-height: 1.8;
}

.total {
    margin-top: 15px;
    font-size: 22px;
    font-weight: bold;
    color: #ff5a00;
}

.tombol {
    display: block;
    margin-top: 25px;
    padding: 14px;
    background: #ff5a00;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: bold;
}

</style>

</head>

<body>

<div class="container">

    <div class="box">

        <div class="icon">
            ✅
        </div>

        <h1>
            Pesanan Berhasil!
        </h1>

        <p>
            Terima kasih sudah berbelanja di Beliin.com
        </p>


        <div class="info">

            <p>
                Nomor Pesanan:
                <strong>
                    #<?= $id_pesanan; ?>
                </strong>
            </p>

            <p>
                Nama:
                <strong>
                    <?= htmlspecialchars($nama); ?>
                </strong>
            </p>

            <p>
                Pembayaran:
                <strong>
                    <?= htmlspecialchars($pembayaran); ?>
                </strong>
            </p>

        </div>


        <div class="total">

            Total:
            Rp <?= number_format(
                $total,
                0,
                ',',
                '.'
            ); ?>

        </div>


        <a
            href="index.php"
            class="tombol"
        >
            Kembali Belanja
        </a>

    </div>

</div>

</body>

</html>