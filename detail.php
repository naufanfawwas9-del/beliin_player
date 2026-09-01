<?php
include "koneksi.php";

if (!isset($_GET['kode']) || $_GET['kode'] == '') {
    die("Kode produk tidak ada.");
}

$kode = $_GET['kode'];

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM produk WHERE kode_produk = ? LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "s", $kode);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Produk tidak ditemukan. Kode: " . htmlspecialchars($kode));
}

$produk = mysqli_fetch_assoc($result);


/* ================= KOMENTAR ================= */

$stmtKomentar = mysqli_prepare(
    $koneksi,
    "SELECT * FROM komentar
     WHERE kode_produk = ?
     ORDER BY created_at DESC"
);

mysqli_stmt_bind_param(
    $stmtKomentar,
    "s",
    $kode
);

mysqli_stmt_execute($stmtKomentar);

$resultKomentar = mysqli_stmt_get_result($stmtKomentar);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
<?= htmlspecialchars($produk['nama']); ?> - Beliin.com
</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    color: #333;
    padding-bottom: 80px;
}


/* HEADER */

.header {
    position: sticky;
    top: 0;
    z-index: 100;
    height: 60px;
    background: white;
    display: flex;
    align-items: center;
    padding: 0 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,.10);
}

.back {
    text-decoration: none;
    color: #333;
    font-size: 30px;
    margin-right: 15px;
}

.header-title {
    font-size: 18px;
    font-weight: bold;
    color: #ff5a00;
}


/* CONTAINER */

.container {
    max-width: 700px;
    margin: auto;
}


/* GAMBAR */

.produk-image {
    width: 100%;
    max-height: 450px;
    object-fit: contain;
    display: block;
    background: white;
}

.no-image {
    width: 100%;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eee;
    font-size: 60px;
}


/* INFORMASI */

.info {
    background: white;
    padding: 18px;
    margin-top: 8px;
}

.nama {
    font-size: 21px;
    font-weight: bold;
    line-height: 1.4;
}

.harga {
    color: #ff5a00;
    font-size: 27px;
    font-weight: bold;
    margin-top: 10px;
}

.info-small {
    color: #999;
    font-size: 12px;
    margin-top: 7px;
}


/* DESKRIPSI */

.description {
    background: white;
    margin-top: 8px;
    padding: 18px;
}

.description-title {
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 12px;
}

.description-text {
    color: #666;
    font-size: 14px;
    line-height: 1.7;
    white-space: pre-line;
}


/* ================= KOMENTAR ================= */

.komentar-box {
    background: white;
    margin-top: 8px;
    padding: 18px;
}

.komentar-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
}


/* FORM */

.komentar-form input,
.komentar-form textarea {
    width: 100%;
    padding: 12px;

    border: 1px solid #ddd;

    border-radius: 8px;

    font-size: 14px;

    font-family: Arial;

    margin-bottom: 10px;

    outline: none;
}

.komentar-form textarea {
    height: 90px;
    resize: none;
}

.komentar-form input:focus,
.komentar-form textarea:focus {
    border-color: #ff5a00;
}

.btn-komentar {
    width: 100%;

    padding: 12px;

    border: none;

    border-radius: 8px;

    background: #ff5a00;

    color: white;

    font-weight: bold;

    cursor: pointer;
}


/* DAFTAR KOMENTAR */

.daftar-komentar {
    margin-top: 20px;
}

.komentar-item {
    padding: 14px 0;

    border-bottom: 1px solid #eee;
}

.komentar-item:last-child {
    border-bottom: none;
}

.komentar-nama {
    font-weight: bold;

    font-size: 14px;

    margin-bottom: 5px;
}

.komentar-isi {
    color: #555;

    font-size: 14px;

    line-height: 1.5;
}

.komentar-tanggal {
    color: #aaa;

    font-size: 11px;

    margin-top: 5px;
}

.komentar-kosong {
    text-align: center;

    color: #999;

    font-size: 14px;

    padding: 20px 0;
}


/* BOTTOM */

.bottom {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;

    height: 70px;

    background: white;

    border-top: 1px solid #ddd;

    display: flex;

    gap: 10px;

    padding: 10px;

    z-index: 200;
}

.cart {
    width: 55px;

    border: 1px solid #ff5a00;

    background: white;

    color: #ff5a00;

    border-radius: 8px;

    font-size: 23px;

    cursor: pointer;
}

.add {
    flex: 1;

    border: 1px solid #ff5a00;

    background: white;

    color: #ff5a00;

    border-radius: 8px;

    font-weight: bold;

    cursor: pointer;
}

.buy {
    flex: 1;

    border: none;

    background: #ff5a00;

    color: white;

    border-radius: 8px;

    font-weight: bold;

    cursor: pointer;
}


/* MODAL */

.modal {
    display: none;

    position: fixed;

    z-index: 500;

    left: 0;

    top: 0;

    width: 100%;

    height: 100%;

    background: rgba(0,0,0,.5);

    align-items: flex-end;

    justify-content: center;
}

.modal-content {
    background: white;

    width: 100%;

    max-width: 700px;

    border-radius: 18px 18px 0 0;

    padding: 20px;

    animation: naik .25s ease;
}

@keyframes naik {

    from {
        transform: translateY(100%);
    }

    to {
        transform: translateY(0);
    }

}

.modal-title {
    font-size: 18px;

    font-weight: bold;

    margin-bottom: 20px;
}

.qty {
    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 20px;
}

.qty button {
    width: 40px;

    height: 40px;

    border: 1px solid #ddd;

    background: white;

    border-radius: 8px;

    font-size: 20px;
}

.qty-number {
    font-size: 18px;

    font-weight: bold;
}

.confirm {
    width: 100%;

    padding: 14px;

    border: none;

    border-radius: 8px;

    background: #ff5a00;

    color: white;

    font-weight: bold;

    cursor: pointer;
}

</style>

</head>

<body>


<!-- HEADER -->

<div class="header">

    <a href="index.php" class="back">
        ‹
    </a>

    <div class="header-title">
        Beliin.com
    </div>

</div>


<div class="container">


    <!-- GAMBAR -->

    <?php if (!empty($produk['gambar'])): ?>

        <img
            src="uploads/<?= htmlspecialchars($produk['gambar']); ?>"
            alt="<?= htmlspecialchars($produk['nama']); ?>"
            class="produk-image"
        >

    <?php else: ?>

        <div class="no-image">
            📦
        </div>

    <?php endif; ?>


    <!-- INFORMASI -->

    <div class="info">

        <div class="nama">

            <?= htmlspecialchars($produk['nama']); ?>

        </div>

        <div class="harga">

            Rp <?= number_format(
                $produk['harga'],
                0,
                ',',
                '.'
            ); ?>

        </div>

        <div class="info-small">

            ⭐ Produk Beliin.com

        </div>

    </div>


    <!-- DESKRIPSI -->

    <div class="description">

        <div class="description-title">
            Deskripsi Produk
        </div>

        <div class="description-text">

            <?php if (!empty($produk['deskripsi'])): ?>

                <?= htmlspecialchars($produk['deskripsi']); ?>

            <?php else: ?>

                Belum ada deskripsi produk.

            <?php endif; ?>

        </div>

    </div>


    <!-- ================= KOMENTAR ================= -->

    <div class="komentar-box">

        <div class="komentar-title">
            💬 Komentar
        </div>


        <!-- FORM KOMENTAR -->

        <form
            action="tambah_komentar.php"
            method="POST"
            class="komentar-form"
        >

            <input
                type="hidden"
                name="kode_produk"
                value="<?= htmlspecialchars($produk['kode_produk']); ?>"
            >


            <input
                type="text"
                name="nama"
                placeholder="Nama kamu"
                maxlength="100"
                required
            >


            <textarea
                name="komentar"
                placeholder="Tulis komentar..."
                required
            ></textarea>


            <button
                type="submit"
                class="btn-komentar"
            >
                💬 Kirim Komentar
            </button>

        </form>


        <!-- DAFTAR KOMENTAR -->

        <div
            class="daftar-komentar"
            id="daftarKomentar"
        >


            <?php if (mysqli_num_rows($resultKomentar) == 0): ?>

                <div class="komentar-kosong">

                    Belum ada komentar.
                    Jadilah yang pertama berkomentar! 😊

                </div>


            <?php else: ?>


                <?php while ($komentar = mysqli_fetch_assoc($resultKomentar)): ?>

                    <div class="komentar-item">

                        <div class="komentar-nama">

                            👤
                            <?= htmlspecialchars(
                                $komentar['nama']
                            ); ?>

                        </div>


                        <div class="komentar-isi">

                            <?= nl2br(
                                htmlspecialchars(
                                    $komentar['komentar']
                                )
                            ); ?>

                        </div>


                        <div class="komentar-tanggal">

                            <?= date(
                                'd-m-Y H:i',
                                strtotime(
                                    $komentar['created_at']
                                )
                            ); ?>

                        </div>

                    </div>

                <?php endwhile; ?>


            <?php endif; ?>


        </div>

    </div>


</div>


<!-- BOTTOM -->

<div class="bottom">

    <button
        class="cart"
        onclick="bukaModal()"
    >
        🛒
    </button>


    <button
        class="add"
        onclick="bukaModal()"
    >
        + Keranjang
    </button>


    <button
        class="buy"
        onclick="bukaModal()"
    >
        Beli Sekarang
    </button>

</div>


<!-- MODAL -->

<div
    class="modal"
    id="modal"
    onclick="tutupModal(event)"
>

    <div class="modal-content">

        <div class="modal-title">

            <?= htmlspecialchars(
                $produk['nama']
            ); ?>

        </div>


        <div class="qty">

            <button onclick="kurang()">
                −
            </button>


            <span
                class="qty-number"
                id="jumlah"
            >
                1
            </span>


            <button onclick="tambah()">
                +
            </button>

        </div>


        <button
            class="confirm"
            onclick="masukkanKeranjang()"
        >
            Masukkan ke Keranjang
        </button>

    </div>

</div>


<script>

let jumlah = 1;


/* BUKA MODAL */

function bukaModal() {

    document.getElementById(
        "modal"
    ).style.display = "flex";

}


/* TUTUP MODAL */

function tutupModal(event) {

    if (event.target.id === "modal") {

        document.getElementById(
            "modal"
        ).style.display = "none";

    }

}


/* KURANG */

function kurang() {

    if (jumlah > 1) {

        jumlah--;

        document.getElementById(
            "jumlah"
        ).innerText = jumlah;

    }

}


/* TAMBAH */

function tambah() {

    jumlah++;

    document.getElementById(
        "jumlah"
    ).innerText = jumlah;

}


/* MASUKKAN KERANJANG */

function masukkanKeranjang() {

    window.location.href =
        "tambah_keranjang.php?kode=<?= urlencode($produk['kode_produk']); ?>&jumlah=" + jumlah;

}

</script>

</body>

</html>