<?php
session_start();

if (isset($_GET['kode'])) {

    $kode = $_GET['kode'];

    if (isset($_SESSION['keranjang'][$kode])) {
        unset($_SESSION['keranjang'][$kode]);
    }
}

header("Location: keranjang.php");
exit;
?>