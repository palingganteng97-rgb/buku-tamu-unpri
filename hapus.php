<?php
$host     = "localhost";
$username = "root";         
$password = "Slebew234";     
$database = "db_buku_tamu"; 

$koneksi = mysqli_connect($host, $username, $password, $database);

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Jalankan query hapus berdasarkan ID
    $sql = "DELETE FROM tamu WHERE id = '$id'";
    
    if (mysqli_query($koneksi, $sql)) {
        header("Location: tabel_tamu.php");
        exit();
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
}

mysqli_close($koneksi);
?>
