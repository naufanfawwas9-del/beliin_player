<?php
session_start();
include "koneksi.php";

$filter = $_GET['status'] ?? 'semua';

if ($filter == 'semua') {

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM pesanan ORDER BY created_at DESC"
    );

} else {

    $status = mysqli_real_escape_string(
        $koneksi,
        $filter
    );

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM pesanan
         WHERE LOWER(status) = LOWER('$status')
         ORDER BY created_at DESC"
    );
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Riwayat - Beliin.com</title>

    <link rel="icon"
          type="image/png"
          href="beliin.png">


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            font-family: Arial, sans-serif;

            background: #f6f6f6;

            color: #333;

            padding-bottom: 85px;
        }


        /* ================= HEADER ================= */

        .header {
            background: white;

            padding: 17px;

            text-align: center;

            font-size: 21px;

            font-weight: bold;

            color: #ff5a00;

            box-shadow:
                0 2px 8px rgba(0,0,0,0.08);
        }


        /* ================= CONTAINER ================= */

        .container {
            max-width: 700px;

            margin: auto;

            padding: 15px;
        }


        /* ================= FILTER ================= */

        .filter {
            background: white;

            border-radius: 10px;

            padding: 12px;

            display: flex;

            gap: 8px;

            margin-bottom: 15px;

            overflow-x: auto;
        }


        .filter a {
            border: none;

            background: #f1f1f1;

            padding: 9px 15px;

            border-radius: 20px;

            white-space: nowrap;

            cursor: pointer;

            font-size: 12px;

            text-decoration: none;

            color: #333;
        }


        .filter a.active {
            background: #ff5a00;

            color: white;
        }


        /* ================= PESANAN ================= */

        .pesanan {
            background: white;

            border-radius: 14px;

            padding: 16px;

            margin-bottom: 12px;

            box-shadow:
                0 3px 10px rgba(0,0,0,0.04);
        }


        .pesanan-header {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 12px;
        }


        .nomor {
            font-size: 17px;

            font-weight: bold;
        }


        .status {
            padding: 6px 10px;

            border-radius: 20px;

            background: #fff3e8;

            color: #ff5a00;

            font-size: 12px;

            font-weight: bold;
        }


        .detail {
            font-size: 14px;

            color: #555;

            line-height: 1.7;

            border-top: 1px solid #eee;

            padding-top: 10px;
        }


        .detail strong {
            color: #333;
        }


        .total {
            display: flex;

            justify-content: space-between;

            margin-top: 12px;

            padding-top: 12px;

            border-top: 1px solid #eee;

            font-size: 17px;

            font-weight: bold;
        }


        .total-harga {
            color: #ff5a00;
        }


        .tanggal {
            margin-top: 8px;

            color: #999;

            font-size: 12px;
        }


        /* ================= KOSONG ================= */

        .kosong {
            background: white;

            border-radius: 14px;

            padding: 60px 20px;

            text-align: center;

            box-shadow:
                0 3px 10px rgba(0,0,0,0.04);
        }


        .kosong-icon {
            font-size: 60px;

            margin-bottom: 15px;
        }


        .kosong h2 {
            margin-bottom: 8px;

            font-size: 20px;
        }


        .kosong p {
            color: #888;

            font-size: 14px;
        }


        .belanja {
            display: inline-block;

            margin-top: 20px;

            padding: 12px 25px;

            background: #ff5a00;

            color: white;

            text-decoration: none;

            border-radius: 25px;

            font-weight: bold;
        }


        /* ================= INFO ================= */

        .info {
            margin-top: 15px;

            background: #fff8f3;

            border: 1px solid #ffe0cc;

            padding: 12px;

            border-radius: 10px;

            color: #777;

            font-size: 12px;

            line-height: 1.5;
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

            z-index: 999;
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


        .nav-item.active {
            color: #ff5a00;

            font-weight: bold;
        }


        /* ================= TAMBAH ================= */

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

            box-shadow:
                0 4px 10px rgba(0,0,0,0.2);
        }

    </style>

</head>


<body>


<!-- ================= HEADER ================= -->

<div class="header">

    🧾 Riwayat Pembelian

</div>


<!-- ================= CONTENT ================= -->

<div class="container">


    <!-- ================= FILTER ================= -->

    <div class="filter">


        <a
            href="riwayat.php?status=semua"
            class="<?= $filter == 'semua' ? 'active' : ''; ?>"
        >
            Semua
        </a>


        <a
            href="riwayat.php?status=menunggu"
            class="<?= $filter == 'menunggu' ? 'active' : ''; ?>"
        >
            Menunggu
        </a>


        <a
            href="riwayat.php?status=diproses"
            class="<?= $filter == 'diproses' ? 'active' : ''; ?>"
        >
            Diproses
        </a>


        <a
            href="riwayat.php?status=dikirim"
            class="<?= $filter == 'dikirim' ? 'active' : ''; ?>"
        >
            Dikirim
        </a>


        <a
            href="riwayat.php?status=selesai"
            class="<?= $filter == 'selesai' ? 'active' : ''; ?>"
        >
            Selesai
        </a>


    </div>


    <!-- ================= DAFTAR PESANAN ================= -->


    <?php if (mysqli_num_rows($query) == 0): ?>


        <div class="kosong">

            <div class="kosong-icon">
                🧾
            </div>

            <h2>
                Belum ada riwayat
            </h2>

            <p>
                Pesanan yang sudah kamu beli
                akan muncul di sini.
            </p>

            <a
                href="index.php"
                class="belanja"
            >
                Mulai Belanja
            </a>

        </div>


    <?php else: ?>


        <?php while ($pesanan = mysqli_fetch_assoc($query)): ?>


            <div class="pesanan">


                <div class="pesanan-header">


                    <div class="nomor">

                        Pesanan #<?= $pesanan['id']; ?>

                    </div>


                    <div class="status">

                        <?= htmlspecialchars(
                            $pesanan['status']
                        ); ?>

                    </div>


                </div>


                <div class="detail">


                    <strong>
                        Nama:
                    </strong>

                    <?= htmlspecialchars(
                        $pesanan['nama']
                    ); ?>

                    <br>


                    <strong>
                        No. HP:
                    </strong>

                    <?= htmlspecialchars(
                        $pesanan['no_hp']
                    ); ?>

                    <br>


                    <strong>
                        Alamat:
                    </strong>

                    <?= htmlspecialchars(
                        $pesanan['alamat']
                    ); ?>

                    <br>


                    <strong>
                        Pembayaran:
                    </strong>

                    <?= htmlspecialchars(
                        $pesanan['pembayaran']
                    ); ?>


                </div>


                <div class="total">


                    <span>
                        Total
                    </span>


                    <span class="total-harga">

                        Rp <?= number_format(
                            $pesanan['total'],
                            0,
                            ',',
                            '.'
                        ); ?>

                    </span>


                </div>


                <div class="tanggal">

                    🕐

                    <?= date(
                        'd-m-Y H:i',
                        strtotime(
                            $pesanan['created_at']
                        )
                    ); ?>

                </div>


            </div>


        <?php endwhile; ?>


    <?php endif; ?>


    <div class="info">

        💡 <b>Info:</b>

        Pesanan yang berhasil dibeli
        akan otomatis tercatat di halaman
        riwayat ini.

    </div>


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
        class="nav-item"
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
        class="nav-item active"
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