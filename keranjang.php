<?php
session_start();
include "koneksi.php";

$keranjang = $_SESSION['keranjang'] ?? [];

$total = 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Keranjang - Beliin.com</title>

    <link rel="icon" type="image/png" href="beliin.png">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f6f6f6;
            padding-bottom: 85px;
        }

        .header {
            background: white;
            padding: 18px;
            text-align: center;
            font-size: 21px;
            font-weight: bold;
            color: #ff5a00;
        }

        .container {
            max-width: 700px;
            margin: auto;
            padding: 15px;
        }


        /* ================= PRODUK ================= */

        .produk {
            position: relative;

            background: white;
            border-radius: 12px;

            padding: 12px;
            padding-right: 55px;

            margin-bottom: 10px;

            display: flex;
            gap: 12px;
        }


        .produk img {
            width: 100px;
            height: 100px;

            object-fit: contain;

            border-radius: 8px;

            background: #f5f5f5;

            flex-shrink: 0;
        }


        .produk-info {
            flex: 1;
            min-width: 0;
        }


        .nama {
            font-size: 16px;
            font-weight: bold;

            margin-bottom: 8px;
        }


        .harga {
            color: #ff5a00;

            font-size: 17px;

            font-weight: bold;
        }


        .jumlah {
            margin-top: 8px;

            color: #777;

            font-size: 14px;
        }


        .subtotal {
            margin-top: 5px;

            font-weight: bold;
        }


        /* ================= TOMBOL HAPUS ================= */

        .hapus {
            position: absolute;

            right: 10px;
            top: 10px;

            width: 36px;
            height: 36px;

            display: flex;

            align-items: center;
            justify-content: center;

            background: #fff0f0;

            color: #e53935;

            border-radius: 8px;

            text-decoration: none;

            font-size: 18px;

            cursor: pointer;
        }


        .hapus:hover {
            background: #ffe0e0;
        }


        /* ================= TOTAL ================= */

        .total-box {
            background: white;

            padding: 18px;

            border-radius: 12px;

            margin-top: 15px;
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


        .checkout {
            display: block;

            width: 100%;

            margin-top: 15px;

            padding: 14px;

            background: #ff5a00;

            color: white;

            border: none;

            border-radius: 10px;

            text-align: center;

            text-decoration: none;

            font-weight: bold;

            font-size: 16px;
        }


        /* ================= KOSONG ================= */

        .kosong {
            background: white;

            border-radius: 12px;

            padding: 60px 20px;

            text-align: center;
        }


        .icon {
            font-size: 60px;

            margin-bottom: 15px;
        }


        .kosong h2 {
            margin-bottom: 8px;
        }


        .kosong p {
            color: #888;
        }


        .belanja {
            display: inline-block;

            margin-top: 20px;

            padding: 12px 25px;

            background: #ff5a00;

            color: white;

            text-decoration: none;

            border-radius: 25px;
        }


        /* ================= BOTTOM NAV ================= */

        .bottom-nav {
            position: fixed;

            bottom: 0;

            left: 0;

            right: 0;

            height: 70px;

            background: white;

            display: flex;

            align-items: center;

            justify-content: space-around;

            border-top: 1px solid #ddd;

            box-shadow:
                0 -3px 15px rgba(0,0,0,0.08);
        }


        .nav-item {
            flex: 1;

            height: 100%;

            display: flex;

            flex-direction: column;

            justify-content: center;

            align-items: center;

            text-decoration: none;

            color: #777;

            font-size: 10px;

            gap: 3px;
        }


        .nav-icon {
            font-size: 21px;
        }


        .active {
            color: #ff5a00;

            font-weight: bold;
        }


        .tambah-icon {
            width: 48px;

            height: 48px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #ff5a00;

            color: white;

            font-size: 29px;

            margin-top: -25px;

            border: 5px solid #f6f6f6;
        }

    </style>

</head>


<body>


<!-- ================= HEADER ================= -->

<div class="header">

    🛒 Keranjang

</div>


<div class="container">


<?php if (empty($keranjang)): ?>


    <!-- ================= KERANJANG KOSONG ================= -->

    <div class="kosong">

        <div class="icon">
            🛒
        </div>

        <h2>
            Keranjang masih kosong
        </h2>

        <p>
            Yuk cari produk yang kamu suka!
        </p>

        <a href="index.php" class="belanja">
            Mulai Belanja
        </a>

    </div>


<?php else: ?>


    <!-- ================= DAFTAR PRODUK ================= -->

    <?php foreach ($keranjang as $kode => $jumlah): ?>

        <?php

        $stmt = mysqli_prepare(
            $koneksi,
            "SELECT * FROM produk WHERE kode_produk = ? LIMIT 1"
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


        $subtotal = $produk['harga'] * $jumlah;


        $total += $subtotal;

        ?>


        <div class="produk">


            <!-- GAMBAR -->

            <?php if (!empty($produk['gambar'])): ?>

                <img
                    src="uploads/<?= htmlspecialchars($produk['gambar']); ?>"
                    alt="<?= htmlspecialchars($produk['nama']); ?>"
                >

            <?php else: ?>

                <img
                    src=""
                    alt="Tidak ada gambar"
                >

            <?php endif; ?>


            <!-- INFORMASI PRODUK -->

            <div class="produk-info">


                <div class="nama">

                    <?= htmlspecialchars(
                        $produk['nama']
                    ); ?>

                </div>


                <div class="harga">

                    Rp <?= number_format(
                        $produk['harga'],
                        0,
                        ',',
                        '.'
                    ); ?>

                </div>


                <div class="jumlah">

                    Jumlah: <?= $jumlah; ?>

                </div>


                <div class="subtotal">

                    Subtotal:
                    Rp <?= number_format(
                        $subtotal,
                        0,
                        ',',
                        '.'
                    ); ?>

                </div>


            </div>


            <!-- TOMBOL HAPUS -->

            <a
                href="hapus_keranjang.php?kode=<?= urlencode($kode); ?>"
                class="hapus"
                onclick="return confirm('Hapus <?= htmlspecialchars($produk['nama']); ?> dari keranjang?')"
                title="Hapus produk"
            >
                🗑️
            </a>


        </div>


    <?php endforeach; ?>


    <!-- ================= TOTAL ================= -->

    <div class="total-box">


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


        <a
            href="checkout.php"
            class="checkout"
        >
            Checkout / Beli
        </a>


    </div>


<?php endif; ?>


</div>


<!-- ================= BOTTOM NAV ================= -->

<nav class="bottom-nav">


    <a
        href="index.php"
        class="nav-item"
    >

        <span class="nav-icon">
            🏠
        </span>

        <span>
            Beranda
        </span>

    </a>


    <a
        href="keranjang.php"
        class="nav-item active"
    >

        <span class="nav-icon">
            🛒
        </span>

        <span>
            Keranjang
        </span>

    </a>


    <a
        href="tambah.php"
        class="nav-item"
    >

        <span class="tambah-icon">
            ＋
        </span>

        <span>
            Tambah
        </span>

    </a>


    <a
        href="riwayat.php"
        class="nav-item"
    >

        <span class="nav-icon">
            🧾
        </span>

        <span>
            Riwayat
        </span>

    </a>


    <a
        href="profil.php"
        class="nav-item"
    >

        <span class="nav-icon">
            👤
        </span>

        <span>
            Profil
        </span>

    </a>


</nav>


</body>

</html>