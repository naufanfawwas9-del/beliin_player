<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Profil - Beliin.com</title>
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        /* ================= CONTAINER ================= */

        .container {
            max-width: 700px;
            margin: auto;
            padding: 15px;
        }

        /* ================= PROFILE ================= */

        .profile {
            background: linear-gradient(
                135deg,
                #ff5a00,
                #ff8a00
            );

            border-radius: 15px;
            padding: 25px 20px;
            color: white;

            display: flex;
            align-items: center;

            gap: 18px;

            margin-bottom: 15px;
        }

        .avatar {
            width: 70px;
            height: 70px;

            border-radius: 50%;

            background: white;
            color: #ff5a00;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 35px;

            flex-shrink: 0;
        }

        .profile-info {
            flex: 1;
        }

        .profile-info h2 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .profile-info p {
            font-size: 13px;
            opacity: 0.9;
        }

        /* ================= LOGIN / LOGOUT ================= */

        .login-button {
            display: inline-block;

            margin-top: 10px;

            padding: 8px 18px;

            background: white;
            color: #ff5a00;

            border-radius: 20px;

            text-decoration: none;

            font-size: 12px;
            font-weight: bold;
        }

        .logout-button {
            display: inline-block;

            margin-top: 10px;

            padding: 8px 18px;

            background: rgba(255,255,255,0.2);

            color: white;

            border: 1px solid white;

            border-radius: 20px;

            text-decoration: none;

            font-size: 12px;

            font-weight: bold;
        }

        /* ================= MENU ================= */

        .menu {
            background: white;

            border-radius: 12px;

            overflow: hidden;

            margin-bottom: 15px;
        }

        .menu-title {
            padding: 15px;

            font-size: 16px;

            font-weight: bold;

            border-bottom: 1px solid #eee;
        }

        .menu-item {
            display: flex;

            align-items: center;

            padding: 16px;

            text-decoration: none;

            color: #333;

            border-bottom: 1px solid #eee;

            transition: 0.2s;
        }

        .menu-item:last-child {
            border-bottom: none;
        }

        .menu-item:hover {
            background: #fafafa;
        }

        .menu-icon {
            width: 35px;

            font-size: 21px;

            margin-right: 10px;
        }

        .menu-text {
            flex: 1;
        }

        .menu-text h3 {
            font-size: 14px;

            margin-bottom: 3px;
        }

        .menu-text p {
            font-size: 11px;

            color: #999;
        }

        .arrow {
            color: #aaa;

            font-size: 20px;
        }

        /* ================= TENTANG ================= */

        .about {
            background: white;

            border-radius: 12px;

            padding: 18px;

            text-align: center;
        }

        .about h3 {
            color: #ff5a00;

            margin-bottom: 7px;
        }

        .about p {
            font-size: 12px;

            color: #888;

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

    👤 Profil Saya

</div>


<!-- ================= CONTENT ================= -->

<div class="container">


    <!-- ================= PROFILE ================= -->

    <div class="profile">


        <div class="avatar">
            👤
        </div>


        <div class="profile-info">


            <?php if (isset($_SESSION['user_id'])): ?>


                <!-- SUDAH LOGIN -->

                <h2>

                    <?= htmlspecialchars(
                        $_SESSION['nama']
                    ); ?>

                </h2>


                <p>

                    <?= htmlspecialchars(
                        $_SESSION['email']
                    ); ?>

                </p>


                <a
                    href="logout.php"
                    class="logout-button"
                    onclick="return confirm('Yakin ingin keluar?')"
                >
                    Keluar
                </a>


            <?php else: ?>


                <!-- BELUM LOGIN -->

                <h2>
                    Pengguna Beliin
                </h2>


                <p>
                    Belum login
                </p>


                <a
                    href="login.php"
                    class="login-button"
                >
                    Login / Daftar
                </a>


            <?php endif; ?>


        </div>


    </div>


    <!-- ================= AKUN ================= -->

    <div class="menu">


        <div class="menu-title">
            Akun Saya
        </div>


        <a
            href="riwayat.php"
            class="menu-item"
        >

            <span class="menu-icon">
                📦
            </span>


            <div class="menu-text">

                <h3>
                    Pesanan Saya
                </h3>

                <p>
                    Lihat semua pesanan
                </p>

            </div>


            <span class="arrow">
                ›
            </span>

        </a>


        <a
            href="#"
            class="menu-item"
            onclick="belumTersedia('Alamat')"
        >

            <span class="menu-icon">
                📍
            </span>


            <div class="menu-text">

                <h3>
                    Alamat Saya
                </h3>

                <p>
                    Atur alamat pengiriman
                </p>

            </div>


            <span class="arrow">
                ›
            </span>

        </a>


        <a
            href="#"
            class="menu-item"
            onclick="belumTersedia('Pengaturan')"
        >

            <span class="menu-icon">
                ⚙️
            </span>


            <div class="menu-text">

                <h3>
                    Pengaturan
                </h3>

                <p>
                    Pengaturan akun
                </p>

            </div>


            <span class="arrow">
                ›
            </span>

        </a>


    </div>


    <!-- ================= BANTUAN ================= -->

    <div class="menu">


        <div class="menu-title">
            Bantuan
        </div>


        <a
            href="#"
            class="menu-item"
            onclick="belumTersedia('Pusat Bantuan')"
        >

            <span class="menu-icon">
                ❓
            </span>


            <div class="menu-text">

                <h3>
                    Pusat Bantuan
                </h3>

                <p>
                    Butuh bantuan?
                </p>

            </div>


            <span class="arrow">
                ›
            </span>

        </a>


        <a
            href="#"
            class="menu-item"
            onclick="belumTersedia('Tentang Beliin')"
        >

            <span class="menu-icon">
                ℹ️
            </span>


            <div class="menu-text">

                <h3>
                    Tentang Beliin.com
                </h3>

                <p>
                    Informasi tentang Beliin
                </p>

            </div>


            <span class="arrow">
                ›
            </span>

        </a>


    </div>


    <!-- ================= ABOUT ================= -->

    <div class="about">

        <h3>
            Beliin.com
        </h3>

        <p>
            Belanja mudah, cepat, dan nyaman.
            <br>
            Versi 1.0
        </p>

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
        class="nav-item active"
    >

        <span class="nav-icon">
            👤
        </span>

        <span>
            Profil
        </span>

    </a>


</nav>


<!-- ================= JAVASCRIPT ================= -->

<script>

function belumTersedia(nama) {

    alert(
        nama + " belum tersedia."
    );

}

</script>


</body>

</html>