<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit(); }

$koneksi = mysqli_connect("localhost", "root", "Slebew234", "db_buku_tamu");

if (isset($_POST['submit'])) {
    $klasifikasi = mysqli_real_escape_string($koneksi, $_POST['klasifikasi']);
    $nama_instansi = mysqli_real_escape_string($koneksi, $_POST['nama_instansi']);

    $query = "INSERT INTO instansi (klasifikasi, nama_instansi) VALUES ('$klasifikasi', '$nama_instansi')";
    if (mysqli_query($koneksi, $query)) {
        header("Location: data_instansi.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Instansi</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow p-4">
            <h4 class="mb-4 fw-bold">Tambah Data Instansi Baru</h4>
            <form action="" method="post">
                <div class="mb-3">
                    <label class="form-label fw-bold">Klasifikasi Lembaga</label>
                    <select name="klasifikasi" class="form-select" required>
                        <option value="Kementerian">Kementerian</option>
                        <option value="Lembaga Tinggi">Lembaga Tinggi / Negara</option>
                        <option value="Lembaga Non-Kementerian">Lembaga Non-Kementerian</option>
                        <option value="Pemerintah Daerah">Pemerintah Daerah (Pemprov/Pemkot)</option>
                        <option value="BUMN">BUMN / Swasta Resmi</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Nama Instansi / Perusahaan Resmi</label>
                    <input type="text" name="nama_instansi" class="form-control" placeholder="Contoh: PT Kereta Api Indonesia (Persero)" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary px-4 fw-bold">Simpan Data</button>
                <a href="data_instansi.php" class="btn btn-secondary px-4 ms-2">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>
