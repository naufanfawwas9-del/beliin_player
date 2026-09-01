<?php

session_start();

/* =========================
   KONEKSI DATABASE
========================= */

$koneksi = mysqli_connect(
    "localhost",
    "root",
    "",
    "beliin"
);

if (!$koneksi) {
    die(
        "Database tidak terhubung: "
        . mysqli_connect_error()
    );
}


$pesan = "";
$tipe = "";
$halaman = "login";


/* =========================
   DAFTAR AKUN
========================= */

if (isset($_POST["daftar"])) {

    $nama = trim($_POST["nama"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    if (
        $nama == "" ||
        $email == "" ||
        $password == ""
    ) {

        $pesan = "Semua data harus diisi.";
        $tipe = "error";
        $halaman = "daftar";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $pesan = "Email tidak valid.";
        $tipe = "error";
        $halaman = "daftar";

    } elseif (strlen($password) < 6) {

        $pesan =
            "Password minimal 6 karakter.";

        $tipe = "error";
        $halaman = "daftar";

    } else {


        /* CEK EMAIL */

        $cek = mysqli_prepare(
            $koneksi,
            "SELECT id FROM users WHERE email = ?"
        );

        mysqli_stmt_bind_param(
            $cek,
            "s",
            $email
        );

        mysqli_stmt_execute($cek);

        mysqli_stmt_store_result($cek);


        if (mysqli_stmt_num_rows($cek) > 0) {

            $pesan =
                "Email tersebut sudah terdaftar.";

            $tipe = "error";
            $halaman = "daftar";

        } else {


            /* ENCRYPT PASSWORD */

            $password_hash =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );


            /* SIMPAN AKUN */

            $stmt = mysqli_prepare(
                $koneksi,
                "INSERT INTO users
                (nama, email, password)
                VALUES (?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $nama,
                $email,
                $password_hash
            );


            if (mysqli_stmt_execute($stmt)) {

                $pesan =
                    "Akun berhasil dibuat! Silakan login.";

                $tipe = "success";

                $halaman = "login";

            } else {

                $pesan =
                    "Gagal membuat akun: "
                    . mysqli_error($koneksi);

                $tipe = "error";

                $halaman = "daftar";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($cek);
    }
}



/* =========================
   LOGIN
========================= */

if (isset($_POST["login"])) {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    if (
        $email == "" ||
        $password == ""
    ) {

        $pesan =
            "Email dan password wajib diisi.";

        $tipe = "error";

    } else {


        $stmt = mysqli_prepare(
            $koneksi,
            "SELECT id, nama, email, password
             FROM users
             WHERE email = ?
             LIMIT 1"
        );


        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $email
        );


        mysqli_stmt_execute($stmt);


        $hasil =
            mysqli_stmt_get_result($stmt);


        if (
            $hasil &&
            mysqli_num_rows($hasil) == 1
        ) {

            $user =
                mysqli_fetch_assoc($hasil);


            if (
                password_verify(
                    $password,
                    $user["password"]
                )
            ) {


                /* LOGIN BERHASIL */

                $_SESSION["user_id"] =
                    $user["id"];

                $_SESSION["nama"] =
                    $user["nama"];

                $_SESSION["email"] =
                    $user["email"];


                header(
                    "Location: profil.php"
                );

                exit;


            } else {

                $pesan =
                    "Password salah.";

                $tipe = "error";
            }


        } else {

            $pesan =
                "Email belum terdaftar.";

            $tipe = "error";
        }


        mysqli_stmt_close($stmt);
    }
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

<title>
    Login - Beliin.com
</title>


<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}


body {

    font-family: Arial, sans-serif;

    background: #f5f5f5;

    min-height: 100vh;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 20px;
}


/* ================= CARD ================= */

.card {

    width: 100%;

    max-width: 400px;

    background: white;

    border-radius: 18px;

    padding: 25px;

    box-shadow:
        0 8px 30px
        rgba(0,0,0,.10);
}


/* ================= LOGO ================= */

.logo {

    text-align: center;

    font-size: 32px;

    font-weight: bold;

    color: #ff5a00;

    margin-bottom: 5px;
}


.subtitle {

    text-align: center;

    color: #999;

    font-size: 13px;

    margin-bottom: 25px;
}


/* ================= TAB ================= */

.tabs {

    display: flex;

    background: #f1f1f1;

    padding: 4px;

    border-radius: 10px;

    margin-bottom: 20px;
}


.tabs button {

    width: 50%;

    border: none;

    padding: 11px;

    border-radius: 8px;

    background: transparent;

    color: #777;

    font-weight: bold;

    cursor: pointer;
}


.tabs button.active {

    background: #ff5a00;

    color: white;
}


/* ================= PESAN ================= */

.pesan {

    padding: 12px;

    border-radius: 9px;

    margin-bottom: 15px;

    text-align: center;

    font-size: 13px;
}


.error {

    background: #ffe8e8;

    color: #d60000;
}


.success {

    background: #e8fff0;

    color: #00852b;
}


/* ================= FORM ================= */

.form-group {

    margin-bottom: 15px;
}


.form-group label {

    display: block;

    font-size: 13px;

    font-weight: bold;

    margin-bottom: 7px;
}


.form-group input {

    width: 100%;

    padding: 13px;

    border: 1px solid #ddd;

    border-radius: 9px;

    font-size: 14px;

    outline: none;
}


.form-group input:focus {

    border-color: #ff5a00;
}


.btn {

    width: 100%;

    border: none;

    padding: 13px;

    border-radius: 9px;

    background: #ff5a00;

    color: white;

    font-size: 15px;

    font-weight: bold;

    cursor: pointer;
}


.btn:hover {

    background: #e94f00;
}


/* ================= KEMBALI ================= */

.kembali {

    display: block;

    text-align: center;

    margin-top: 20px;

    text-decoration: none;

    color: #888;

    font-size: 13px;
}


.kembali:hover {

    color: #ff5a00;
}


/* ================= PASSWORD ================= */

.password-box {

    position: relative;
}


.password-box input {

    padding-right: 50px;
}


.password-box button {

    position: absolute;

    right: 10px;

    top: 50%;

    transform: translateY(-50%);

    border: none;

    background: transparent;

    cursor: pointer;

    font-size: 18px;
}

</style>

</head>


<body>


<div class="card">


    <div class="logo">
        Beliin
    </div>


    <div class="subtitle">
        Belanja mudah, cepat, dan nyaman
    </div>



    <!-- ================= TAB ================= -->

    <div class="tabs">

        <button
            id="btnLogin"
            class="<?= $halaman == 'login'
                ? 'active'
                : ''; ?>"
            onclick="showLogin()"
        >
            Login
        </button>


        <button
            id="btnDaftar"
            class="<?= $halaman == 'daftar'
                ? 'active'
                : ''; ?>"
            onclick="showDaftar()"
        >
            Daftar
        </button>

    </div>



    <!-- ================= PESAN ================= -->

    <?php if ($pesan != ""): ?>

        <div
            class="pesan <?= $tipe; ?>"
        >

            <?= htmlspecialchars($pesan); ?>

        </div>

    <?php endif; ?>



    <!-- ================= LOGIN ================= -->

    <form
        method="POST"
        id="formLogin"
        style="<?= $halaman == 'login'
            ? ''
            : 'display:none;'; ?>"
    >


        <div class="form-group">

            <label>
                Email
            </label>

            <input
                type="email"
                name="email"
                placeholder="Masukkan email"
                required
            >

        </div>


        <div class="form-group">

            <label>
                Password
            </label>


            <div class="password-box">

                <input
                    type="password"
                    name="password"
                    id="passwordLogin"
                    placeholder="Masukkan password"
                    required
                >


                <button
                    type="button"
                    onclick="lihatPassword('passwordLogin')"
                >
                    👁
                </button>

            </div>

        </div>


        <button
            type="submit"
            name="login"
            class="btn"
        >
            Login
        </button>


    </form>



    <!-- ================= DAFTAR ================= -->

    <form
        method="POST"
        id="formDaftar"
        style="<?= $halaman == 'daftar'
            ? ''
            : 'display:none;'; ?>"
    >


        <div class="form-group">

            <label>
                Nama
            </label>

            <input
                type="text"
                name="nama"
                placeholder="Masukkan nama"
                required
            >

        </div>


        <div class="form-group">

            <label>
                Email
            </label>

            <input
                type="email"
                name="email"
                placeholder="Masukkan email"
                required
            >

        </div>


        <div class="form-group">

            <label>
                Password
            </label>


            <div class="password-box">

                <input
                    type="password"
                    name="password"
                    id="passwordDaftar"
                    placeholder="Minimal 6 karakter"
                    minlength="6"
                    required
                >


                <button
                    type="button"
                    onclick="lihatPassword('passwordDaftar')"
                >
                    👁
                </button>

            </div>

        </div>


        <button
            type="submit"
            name="daftar"
            class="btn"
        >
            Buat Akun
        </button>


    </form>



    <a
        href="profil.php"
        class="kembali"
    >
        ← Kembali ke Profil
    </a>


</div>



<script>


/* =================
   TAMPIL LOGIN
================= */

function showLogin() {

    document.getElementById(
        "formLogin"
    ).style.display = "block";


    document.getElementById(
        "formDaftar"
    ).style.display = "none";


    document.getElementById(
        "btnLogin"
    ).classList.add("active");


    document.getElementById(
        "btnDaftar"
    ).classList.remove("active");

}



/* =================
   TAMPIL DAFTAR
================= */

function showDaftar() {

    document.getElementById(
        "formLogin"
    ).style.display = "none";


    document.getElementById(
        "formDaftar"
    ).style.display = "block";


    document.getElementById(
        "btnLogin"
    ).classList.remove("active");


    document.getElementById(
        "btnDaftar"
    ).classList.add("active");

}



/* =================
   LIHAT PASSWORD
================= */

function lihatPassword(id) {

    const input =
        document.getElementById(id);


    if (input.type === "password") {

        input.type = "text";

    } else {

        input.type = "password";

    }

}

</script>


</body>

</html>