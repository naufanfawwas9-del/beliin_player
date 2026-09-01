<?php
include "koneksi.php";

if (
    !isset($_POST['kode_produk']) ||
    !isset($_POST['nama']) ||
    !isset($_POST['komentar'])
) {
    die("Data komentar tidak lengkap.");
}

$kode_produk = trim($_POST['kode_produk']);
$nama = trim($_POST['nama']);
$komentar = trim($_POST['komentar']);

if ($kode_produk == '' || $nama == '' || $komentar == '') {
    die("Semua data harus diisi.");
}

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO komentar
    (kode_produk, nama, komentar)
    VALUES (?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $kode_produk,
    $nama,
    $komentar
);

if (mysqli_stmt_execute($stmt)) {

    header(
        "Location: detail.php?kode=" .
        urlencode($kode_produk)
    );

    exit;

} else {

    die(
        "Komentar gagal disimpan: " .
        mysqli_error($koneksi)
    );

}
?>