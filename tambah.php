<?php
include "koneksi.php";

$pesan = "";

if (isset($_POST['simpan'])) {

    $kode_produk = mysqli_real_escape_string(
        $koneksi,
        $_POST['kode_produk']
    );

    $nama = mysqli_real_escape_string(
        $koneksi,
        $_POST['nama']
    );

    $harga = mysqli_real_escape_string(
        $koneksi,
        $_POST['harga']
    );

    $deskripsi = mysqli_real_escape_string(
        $koneksi,
        $_POST['deskripsi']
    );

    $stok = mysqli_real_escape_string(
        $koneksi,
        $_POST['stok']
    );


    /* ================= GAMBAR ================= */

    $nama_gambar = "";

    if (isset($_FILES['gambar']) &&
        $_FILES['gambar']['error'] == 0) {

        $nama_asli = $_FILES['gambar']['name'];

        $tmp = $_FILES['gambar']['tmp_name'];

        $ukuran = $_FILES['gambar']['size'];

        $ekstensi = strtolower(
            pathinfo(
                $nama_asli,
                PATHINFO_EXTENSION
            )
        );


        $ekstensi_diizinkan = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];


        if (!in_array(
            $ekstensi,
            $ekstensi_diizinkan
        )) {

            $pesan = "Format gambar harus JPG, JPEG, PNG, atau WEBP.";

        } elseif ($ukuran > 5 * 1024 * 1024) {

            $pesan = "Ukuran gambar maksimal 5 MB.";

        } else {

            $nama_gambar =
                uniqid("produk_") .
                "." .
                $ekstensi;


            if (!is_dir("uploads")) {
                mkdir("uploads", 0777, true);
            }


            move_uploaded_file(
                $tmp,
                "uploads/" . $nama_gambar
            );
        }

    }


    /* ================= SIMPAN ================= */

    if ($pesan == "") {

        $cek = mysqli_query(
            $koneksi,
            "SELECT * FROM produk
             WHERE kode_produk='$kode_produk'"
        );


        if (mysqli_num_rows($cek) > 0) {

            $pesan =
                "Kode produk sudah digunakan.";

        } else {

            $sql = "INSERT INTO produk
                    (kode_produk, nama, harga, gambar, deskripsi, stok)
                    VALUES
                    ('$kode_produk',
                     '$nama',
                     '$harga',
                     '$nama_gambar',
                     '$deskripsi',
                     '$stok')";


            if (mysqli_query($koneksi, $sql)) {

                header(
                    "Location: index.php"
                );

                exit;

            } else {

                $pesan =
                    "Produk gagal ditambahkan: "
                    . mysqli_error($koneksi);
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tambah Produk - Beliin.com</title>
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

            padding-bottom: 90px;
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


        /* ================= FORM ================= */

        .container {
            max-width: 650px;

            margin: 20px auto;

            padding: 0 15px;
        }


        .form-box {
            background: white;

            padding: 22px;

            border-radius: 14px;

            box-shadow:
                0 3px 12px rgba(0,0,0,0.06);
        }


        .form-box h2 {
            margin-bottom: 20px;

            font-size: 20px;
        }


        .form-group {
            margin-bottom: 16px;
        }


        .form-group label {
            display: block;

            margin-bottom: 7px;

            font-size: 14px;

            font-weight: bold;
        }


        .form-group input,
        .form-group textarea {

            width: 100%;

            padding: 12px;

            border: 1px solid #ddd;

            border-radius: 8px;

            outline: none;

            font-size: 14px;
        }


        .form-group input:focus,
        .form-group textarea:focus {

            border-color: #ff5a00;
        }


        textarea {
            min-height: 120px;

            resize: vertical;
        }


        /* ================= PREVIEW GAMBAR ================= */

        .preview {

            width: 100%;

            height: 220px;

            border: 2px dashed #ddd;

            border-radius: 10px;

            display: flex;

            justify-content: center;

            align-items: center;

            overflow: hidden;

            margin-top: 10px;

            color: #999;
        }


        .preview img {

            width: 100%;

            height: 100%;

            object-fit: contain;

            display: none;
        }


        /* ================= PESAN ================= */

        .pesan {

            background: #ffe5e5;

            color: #d00000;

            padding: 12px;

            border-radius: 8px;

            margin-bottom: 15px;

            font-size: 14px;
        }


        /* ================= BUTTON ================= */

        .btn {

            width: 100%;

            padding: 13px;

            border: none;

            border-radius: 8px;

            background: #ff5a00;

            color: white;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;
        }


        .btn:hover {

            background: #e94f00;
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

            box-shadow:
                0 4px 10px rgba(0,0,0,0.2);
        }

    </style>

</head>


<body>


<!-- ================= HEADER ================= -->

<div class="header">

    ➕ Tambah Produk

</div>



<!-- ================= FORM ================= -->

<div class="container">

    <div class="form-box">

        <h2>
            Tambahkan Produk Baru
        </h2>


        <?php if ($pesan != ""): ?>

            <div class="pesan">
                <?= htmlspecialchars($pesan); ?>
            </div>

        <?php endif; ?>


        <form
            method="POST"
            enctype="multipart/form-data"
        >


            <!-- KODE PRODUK -->

            <div class="form-group">

                <label>
                    Kode Produk
                </label>

                <input
                    type="text"
                    name="kode_produk"
                    placeholder="Contoh: PRD001"
                    required
                >

            </div>


            <!-- NAMA -->

            <div class="form-group">

                <label>
                    Nama Produk
                </label>

                <input
                    type="text"
                    name="nama"
                    placeholder="Masukkan nama produk"
                    required
                >

            </div>


            <!-- HARGA -->

            <div class="form-group">

                <label>
                    Harga
                </label>

                <input
                    type="number"
                    name="harga"
                    placeholder="Contoh: 150000"
                    min="0"
                    required
                >

            </div>


            <!-- GAMBAR -->

            <div class="form-group">

                <label>
                    Gambar Produk
                </label>

                <input
                    type="file"
                    name="gambar"
                    id="gambar"
                    accept="image/jpeg,image/png,image/webp"
                    required
                >


                <div class="preview">

                    <span id="previewText">
                        Preview gambar
                    </span>

                    <img
                        id="previewImage"
                        src=""
                        alt="Preview"
                    >

                </div>

            </div>


            <!-- DESKRIPSI -->

            <div class="form-group">

                <label>
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    placeholder="Jelaskan produk..."
                    required
                ></textarea>

            </div>


            <!-- STOK -->

            <div class="form-group">

                <label>
                    Stok
                </label>

                <input
                    type="number"
                    name="stok"
                    placeholder="Contoh: 10"
                    min="0"
                    required
                >

            </div>


            <button
                type="submit"
                name="simpan"
                class="btn"
            >
                Simpan Produk
            </button>


        </form>

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
        class="nav-item active"
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



<!-- ================= JAVASCRIPT ================= -->

<script>

    const gambar =
        document.getElementById("gambar");

    const previewImage =
        document.getElementById("previewImage");

    const previewText =
        document.getElementById("previewText");


    gambar.addEventListener(
        "change",
        function() {

            const file =
                this.files[0];


            if (file) {

                const reader =
                    new FileReader();


                reader.onload =
                    function(e) {

                        previewImage.src =
                            e.target.result;

                        previewImage.style.display =
                            "block";

                        previewText.style.display =
                            "none";
                    };


                reader.readAsDataURL(file);

            }

        }
    );

</script>


</body>

</html>