<?php
session_start();
// Pastikan hanya yang sudah login yang bisa memproses update database
if (!isset($_SESSION['login'])) {
    echo "Akses ditolak";
    exit();
}

$host     = "localhost";
$username = "root";         
$password = "Slebew234";     
$database = "db_buku_tamu"; 

$koneksi = mysqli_connect($host, $username, $password, $database);

if (isset($_POST['id']) && isset($_POST['nama_instansi'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $nama_instansi = mysqli_real_escape_string($koneksi, $_POST['nama_instansi']);

    // Validasi agar pengguna tidak sengaja mengosongkan nama instansi
    if (empty($nama_instansi)) {
        echo "Nama instansi tidak boleh kosong!";
        exit();
    }

    // Jalankan query update data
    $sql = "UPDATE data_instansi SET nama_instansi = '$nama_instansi' WHERE id = '$id'";
    if (mysqli_query($koneksi, $sql)) {
        echo "sukses"; // Mengembalikan string sukses ke AJAX Jquery
    } else {
        echo mysqli_error($koneksi);
    }
}

mysqli_close($koneksi);
?>
