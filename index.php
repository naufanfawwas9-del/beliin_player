<?php
include "koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM produk");

if (!$query) {
    die("Error database: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Beliin.com</title>
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

header {
    background: white;
    position: sticky;
    top: 0;
    z-index: 100;

    box-shadow:
        0 2px 10px rgba(0,0,0,0.08);
}


.header {
    max-width: 1200px;
    margin: auto;

    padding: 13px 15px;

    display: flex;
    align-items: center;

    gap: 15px;
}


.logo {
    font-size: 25px;
    font-weight: bold;
    color: #ff5a00;

    white-space: nowrap;
}


.search {
    flex: 1;
    position: relative;
}


.search input {
    width: 100%;

    padding: 12px 45px 12px 17px;

    border: 1px solid #ddd;

    border-radius: 25px;

    outline: none;

    font-size: 14px;
}


.search input:focus {
    border-color: #ff5a00;
}


.search button {
    position: absolute;

    right: 4px;
    top: 4px;

    width: 35px;
    height: 35px;

    border: none;

    border-radius: 50%;

    background: #ff5a00;

    color: white;

    cursor: pointer;
}


.cart-top {
    font-size: 24px;

    text-decoration: none;

    color: #333;
}


/* ================= CONTAINER ================= */

.container {
    max-width: 1200px;

    margin: auto;

    padding: 15px;
}


/* ================= BANNER ================= */

.banner {
    background:
        linear-gradient(
            135deg,
            #ff5a00,
            #ff8a00
        );

    color: white;

    border-radius: 15px;

    padding: 30px 25px;

    min-height: 190px;

    display: flex;

    align-items: center;

    position: relative;

    overflow: hidden;
}


.banner h1 {
    font-size: 30px;

    margin-bottom: 8px;
}


.banner p {
    font-size: 14px;

    margin-bottom: 15px;
}


.banner button {
    border: none;

    background: white;

    color: #ff5a00;

    padding: 10px 18px;

    border-radius: 20px;

    font-weight: bold;

    cursor: pointer;
}


.circle {
    position: absolute;

    width: 230px;
    height: 230px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.15);

    right: -60px;
    top: -40px;
}


/* ================= SECTION ================= */

.section {
    margin-top: 25px;
}


.title {
    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 12px;
}


.title h2 {
    font-size: 20px;
}


/* ================= KATEGORI ================= */

.kategori {
    display: grid;

    grid-template-columns:
        repeat(5, 1fr);

    gap: 10px;
}


.kategori-item {
    background: white;

    border-radius: 12px;

    padding: 15px 5px;

    text-align: center;

    cursor: pointer;

    transition: 0.2s;
}


.kategori-item:hover {
    transform: translateY(-3px);
}


.kategori-icon {
    font-size: 28px;

    margin-bottom: 6px;
}


.kategori-item p {
    font-size: 12px;
}


/* ================= PRODUK ================= */

.produk-container {
    display: grid;

    grid-template-columns:
        repeat(5, 1fr);

    gap: 13px;
}


.produk {
    background: white;

    border-radius: 10px;

    overflow: hidden;

    text-decoration: none;

    color: #333;

    transition: 0.2s;

    box-shadow:
        0 2px 6px rgba(0,0,0,0.05);
}


.produk:hover {
    transform: translateY(-4px);

    box-shadow:
        0 7px 18px rgba(0,0,0,0.12);
}


.produk img {
    width: 100%;

    height: 180px;

    object-fit: cover;

    display: block;
}


.no-gambar {
    height: 180px;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #eee;

    color: #999;

    font-size: 40px;
}


.info {
    padding: 10px;
}


.nama {
    font-size: 14px;

    line-height: 1.4;

    height: 40px;

    overflow: hidden;

    margin-bottom: 7px;
}


.harga {
    color: #ff5a00;

    font-size: 17px;

    font-weight: bold;

    margin-bottom: 5px;
}


.rating {
    font-size: 11px;

    color: #999;
}


.stok {
    font-size: 11px;

    color: #888;

    margin-top: 4px;
}


/* ================= PRODUK KOSONG ================= */

.kosong {
    grid-column: 1 / -1;

    background: white;

    padding: 50px 20px;

    text-align: center;

    border-radius: 12px;

    color: #888;
}


.kosong-icon {
    font-size: 50px;

    margin-bottom: 10px;
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

    justify-content: space-around;

    align-items: center;

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

.nav-tambah {
    position: relative;
}


.tambah-icon {
    width: 48px;
    height: 48px;

    border-radius: 50%;

    display: flex;

    justify-content: center;

    align-items: center;

    background: #ff5a00;

    color: white;

    font-size: 29px;

    margin-top: -25px;

    border: 5px solid #f6f6f6;

    box-shadow:
        0 4px 10px rgba(0,0,0,0.2);
}


/* ================= RESPONSIVE ================= */

@media (max-width: 900px) {

    .produk-container {
        grid-template-columns:
            repeat(3, 1fr);
    }

}


@media (max-width: 700px) {

    .header {
        flex-wrap: wrap;
    }


    .logo {
        font-size: 10px;
    }


    .search {
        order: 3;

        flex-basis: 100%;
    }


    .container {
        padding: 12px;
    }


    .banner {
        min-height: 170px;

        padding: 25px 20px;
    }


    .banner h1 {
        font-size: 25px;
    }


    .kategori {
        grid-template-columns:
            repeat(5, 1fr);
    }


    .produk-container {
        grid-template-columns:
            repeat(2, 1fr);

        gap: 10px;
    }


    .produk img,
    .no-gambar {
        height: 165px;
    }


    .nama {
        font-size: 13px;
    }


    .harga {
        font-size: 16px;
    }

}
.Logo {
    width: 40px;
    height: 40px;
    object-fit: contain;
    position: absolute;
    top: 10px;
    left: 15px;
}
.logo {
    width: 100px;
    height: 42px;
    object-fit: contain;
}

</style>

</head>


<body>


<!-- ================= HEADER ================= -->

<header>

    <div class="header">

        <div class="logo">
           <img src="beliin.png" alt="beliin" class="logo">
        </div>


        <div class="search">

            <input
                type="text"
                id="searchInput"
                placeholder="Cari produk..."
            >


            <button
                id="searchButton"
            >
                🔍
            </button>

        </div>


        <a
            href="keranjang.php"
            class="cart-top"
        >
            🛒
        </a>

    </div>

</header>



<!-- ================= CONTENT ================= -->

<div class="container">


    <!-- BANNER -->

    <div class="banner">
        <div class="circle"></div>

    </div>


    <!-- ================= PRODUK ================= -->

    <div
        class="section"
        id="produkSection"
    >


        <div class="title">

            <h2>
                Produk Terbaru
            </h2>

        </div>



        <div
            class="produk-container"
            id="produkContainer"
        >


            <?php if (
                mysqli_num_rows($query) > 0
            ): ?>


                <?php while (
                    $produk =
                    mysqli_fetch_assoc($query)
                ): ?>


                    <!--
                        PRODUK DIKLIK
                        AKAN MEMBUKA detail.php
                    -->

                    <a
    href="detail.php?kode=<?= urlencode($produk['kode_produk']); ?>"
    class="produk"
>


                        <?php if (
                            !empty($produk['gambar'])
                        ): ?>


                            <img
                                src="uploads/<?= htmlspecialchars(
                                    $produk['gambar']
                                ); ?>"
                                alt="<?= htmlspecialchars(
                                    $produk['nama']
                                ); ?>"
                            >


                        <?php else: ?>


                            <div class="no-gambar">
                                📦
                            </div>


                        <?php endif; ?>



                        <div class="info">


                            <h3 class="nama">

                                <?= htmlspecialchars(
                                    $produk['nama']
                                ); ?>

                            </h3>



                            <p class="harga">

                                Rp
                                <?= number_format(
                                    $produk['harga'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>

                            </p>



                            <p class="rating">

                                ⭐ 5.0 • Produk baru

                            </p>



                            <?php
                            /*
                             * Kalau kolom stok ada,
                             * tampilkan stok.
                             */
                            ?>

                            <?php if (
                                isset($produk['stok'])
                            ): ?>

                                <p class="stok">

                                    Stok:
                                    <?= htmlspecialchars(
                                        $produk['stok']
                                    ); ?>

                                </p>

                            <?php endif; ?>


                        </div>


                    </a>


                <?php endwhile; ?>


            <?php else: ?>


                <div class="kosong">

                    <div class="kosong-icon">
                        🛍️
                    </div>


                    <h3>
                        Belum ada produk
                    </h3>


                    <p>
                        Produk yang kamu tambahkan
                        akan muncul di sini.
                    </p>

                </div>


            <?php endif; ?>


        </div>

    </div>


</div>



<!-- ================= BOTTOM NAV ================= -->

<nav class="bottom-nav">


    <a
        href="index.php"
        class="nav-item active"
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
        class="nav-item nav-tambah"
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



<script>

/* =========================
   PENCARIAN
========================= */

const searchInput =
    document.getElementById(
        "searchInput"
    );


const searchButton =
    document.getElementById(
        "searchButton"
    );


const produk =
    document.querySelectorAll(
        ".produk"
    );


function cariProduk() {

    const kataKunci =
        searchInput.value
        .toLowerCase()
        .trim();


    produk.forEach(function(item) {

        const nama =
            item.querySelector(
                ".nama"
            )
            .textContent
            .toLowerCase();


        if (
            nama.includes(kataKunci)
        ) {

            item.style.display = "";

        } else {

            item.style.display = "none";

        }

    });

}


searchInput.addEventListener(
    "input",
    cariProduk
);


searchButton.addEventListener(
    "click",
    cariProduk
);


/* =========================
   MULAI BELANJA
========================= */

document
    .getElementById("belanjaButton")
    .addEventListener(
        "click",
        function() {

            document
                .getElementById(
                    "produkSection"
                )
                .scrollIntoView({
                    behavior: "smooth"
                });

        }
    );


/* =========================
   KATEGORI
========================= */

const kategori =
    document.querySelectorAll(
        ".kategori-item"
    );


kategori.forEach(function(item) {

    item.addEventListener(
        "click",
        function() {

            const namaKategori =
                this.dataset.kategori
                .toLowerCase();


            searchInput.value =
                namaKategori;


            cariProduk();


            document
                .getElementById(
                    "produkSection"
                )
                .scrollIntoView({
                    behavior: "smooth"
                });

        }
    );

});

</script>


</body>

</html>