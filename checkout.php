<?php
session_start();
include "koneksi.php";

$keranjang = $_SESSION['keranjang'] ?? [];

if (empty($keranjang)) {
    header("Location: keranjang.php");
    exit;
}

$total = 0;

foreach ($keranjang as $kode => $jumlah) {

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT * FROM produk WHERE kode_produk = ? LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, "s", $kode);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        continue;
    }

    $produk = mysqli_fetch_assoc($result);

    $total += $produk['harga'] * $jumlah;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Checkout - Beliin.com</title>

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

.header {
    background: white;
    padding: 18px;
    text-align: center;
    color: #ff5a00;
    font-size: 21px;
    font-weight: bold;
}

.container {
    max-width: 700px;
    margin: auto;
    padding: 15px;
}

.box {
    background: white;
    padding: 18px;
    border-radius: 12px;
    margin-bottom: 12px;
}

.title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
}

label {
    display: block;
    margin-top: 12px;
    margin-bottom: 6px;
    font-weight: bold;
}

input,
textarea,
select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
}

textarea {
    height: 90px;
    resize: none;
}

.total {
    display: flex;
    justify-content: space-between;
    font-size: 19px;
    font-weight: bold;
}

.total-harga {
    color: #ff5a00;
}


/* ================= KEMBALI ================= */

.kembali {
    display: block;
    width: 100%;

    padding: 14px;

    margin-top: 15px;

    background: white;

    color: #ff5a00;

    border: 1px solid #ff5a00;

    border-radius: 10px;

    text-align: center;

    text-decoration: none;

    font-size: 16px;

    font-weight: bold;
}


/* ================= BUAT PESANAN ================= */

.beli {
    width: 100%;

    padding: 14px;

    border: none;

    border-radius: 10px;

    background: #ff5a00;

    color: white;

    font-size: 16px;

    font-weight: bold;

    margin-top: 10px;

    cursor: pointer;
}

</style>

</head>

<body>


<div class="header">
    🛍️ Checkout
</div>


<div class="container">


<form action="proses_beli.php" method="POST">


    <!-- DATA PEMBELI -->

    <div class="box">

        <div class="title">
            📍 Data Pembeli
        </div>


        <label>
            Nama
        </label>

        <input
            type="text"
            name="nama"
            placeholder="Masukkan nama"
            required
        >


        <label>
            Nomor HP
        </label>

        <input
            type="tel"
            name="no_hp"
            placeholder="08xxxxxxxxxx"
            required
        >


        <label>
            Alamat
        </label>

        <textarea
            name="alamat"
            placeholder="Masukkan alamat lengkap"
            required
        ></textarea>

    </div>


    <!-- PEMBAYARAN -->

    <div class="box">

        <div class="title">
            💳 Metode Pembayaran
        </div>


        <select
            name="pembayaran"
            required
        >

            <option value="">
                Pilih pembayaran
            </option>

            <option value="COD">
                COD
            </option>

            <option value="Transfer">
                Transfer
            </option>

        </select>

    </div>


    <!-- TOTAL -->

    <div class="box">

        <div class="total">

            <span>
                Total
            </span>


            <span class="total-harga">

                Rp <?= number_format(
                    $total,
                    0,
                    ',',
                    '.'
                ); ?>

            </span>

        </div>


        <!-- TOMBOL KEMBALI -->

        <a
            href="keranjang.php"
            class="kembali"
        >
            ← Kembali ke Keranjang
        </a>


        <!-- TOMBOL BELI -->

        <button
            type="submit"
            class="beli"
        >
            🛒 Buat Pesanan
        </button>

    </div>


</form>


</div>


</body>

</html>