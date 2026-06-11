<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit(); }

$koneksi = mysqli_connect("localhost", "root", "Slebew234", "db_buku_tamu");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM instansi WHERE id = $id";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: data_instansi.php");
        exit();
    }
}
?>
