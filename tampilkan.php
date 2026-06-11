<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$host     = "localhost";
$username = "root";         
$password = "Slebew234";     
$database = "db_buku_tamu"; 
$koneksi = mysqli_connect("localhost", "root", "Slebew234", "db_buku_tamu");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$sql = "SELECT id, instansi, tanggal_kunjungan FROM tamu";
$result = mysqli_query($koneksi, $sql);
$total_tamu = mysqli_num_rows($result);

$instansi_unik = 0;
$kunjungan_hari_ini = 0;

if ($total_tamu > 0) {
    $array_instansi = [];
    $hari_ini = date('Y-m-d');
    
    while($row = mysqli_fetch_assoc($result)) {
        if (!in_array($row['instansi'], $array_instansi)) {
            $array_instansi[] = $row['instansi'];
        }
        $tgl_tamu = date('Y-m-d', strtotime($row['tanggal_kunjungan']));
        if ($tgl_tamu == $hari_ini) {
            $kunjungan_hari_ini++;
        }
    }
    $instansi_unik = count($array_instansi);
}
mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Buku Tamu</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { background-color: #f4f6f9; font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .top-navbar { background-color: #2c5e8a; color: white; padding: 15px 20px; font-size: 16px; font-weight: bold; text-align: center; }
        .dashboard-layout { display: flex; min-height: calc(100vh - 54px); }
        
        /* MODIFIKASI SIDEBAR MENJADI FLEXBOX */
        .sidebar-menu { 
            width: 220px; 
            background-color: #3e3e3e; 
            flex-shrink: 0; 
            padding-top: 10px; 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; /* Membuat isi terbagi atas dan bawah */
        }
        .menu-top-group { width: 100%; }
        .sidebar-menu a { display: block; color: #dfdfdf; padding: 15px 20px; text-decoration: none; border-bottom: 1px solid #4f4f4f; font-size: 13px; font-weight: bold; text-transform: uppercase; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: #2c5e8a; color: white; }
        
        /* Style Khusus Tombol Logout di Paling Bawah */
        .sidebar-menu .logout-link { border-top: 1px solid #4f4f4f; border-bottom: none; color: #ff8888; }
        .sidebar-menu .logout-link:hover { background-color: #d9534f; color: white; }

        .main-container { flex-grow: 1; padding: 25px; overflow-x: hidden; }
        .page-title { font-size: 20px; font-weight: bold; color: #333; text-transform: uppercase; margin-bottom: 5px; }
        .welcome-box { background: white; padding: 15px 20px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 25px; border-left: 4px solid #2c5e8a; }
        .welcome-box h4 { margin: 0 0 5px 0; font-size: 14px; color: #777; }
        .welcome-box h2 { margin: 0; font-size: 18px; font-weight: bold; color: #222; text-transform: uppercase; }
        .stat-row { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 25px; }
        .stat-col { flex: 1; min-width: 200px; }
        .stat-box { border-radius: 4px; color: white; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-main { padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .stat-count { font-size: 32px; font-weight: bold; }
        .stat-label { font-size: 12px; text-transform: uppercase; opacity: 0.9; margin-top: 5px; }
        .stat-footer { background: rgba(0, 0, 0, 0.12); padding: 8px 15px; font-size: 11px; display: flex; justify-content: space-between; }
        .stat-footer a { color: white; text-decoration: none; }
        .bg-blue { background-color: #337ab7; }
        .bg-green { background-color: #5cb85c; }
        .bg-red { background-color: #d9534f; }
        .bg-yellow { background-color: #f0ad4e; }
        @media (max-width: 768px) { .dashboard-layout { flex-direction: column; } .sidebar-menu { width: 100%; min-height: auto; } }
    </style>
</head>
<body>

    <div class="top-navbar">
        Sistem Informasi Buku Tamu Universitas Prima Indonesia
    </div>

    <div class="dashboard-layout">
        <!-- SIDEBAR KIRI DENGAN STRUKTUR BARU -->
        <div class="sidebar-menu">
    <div class="menu-top-group">
        <a class="active" href="tampilkan.php">Dashboard</a>
        <a href="tabel_tamu.php">Tabel Tamu</a>
        <a href="data_instansi.php">Data Instansi</a> <!-- Tambahkan baris ini -->
        <a href="tambah.php">Tambah Tamu</a>
    </div>
    <a href="logout.php" class="logout-link">Logout</a>
</div>

       <!-- Wadah Putih Utama ala Tabel Tamu -->
<div style="background: #fff; padding: 25px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-top: 20px;">
    
    <!-- Judul Halaman di Dalam Wadah -->
    <h2 style="margin-top: 0; margin-bottom: 5px; font-weight: 600; color: #333;">DASHBOARD</h2>
    <p style="color: #777; margin-bottom: 25px; font-size: 14px;">Ringkasan Aktivitas Buku Tamu</p>
    
    <!-- Kotak Selamat Datang -->
    <div style="background: #f8f9fc; border-left: 4px solid #4e73df; padding: 15px; border-radius: 4px; margin-bottom: 30px;">
        <span style="color: #5a5c69; font-size: 14px; display: block; margin-bottom: 2px;">Selamat Datang,</span>
        <strong style="color: #3a3b45; font-size: 20px; font-weight: 700;"><?php echo htmlspecialchars(strtoupper($_SESSION['admin_user'])); ?></strong>
    </div>

    <!-- Blok Utama Kotak Statistik (Baris 131 di foto Anda) -->
       <!-- Blok Utama Kotak Statistik -->
    <div class="stat-row">
        
        <!-- Kotak 1: Total Tamu (Biru) -->
        <div class="stat-col">
            <div class="stat-box bg-blue">
                <div class="stat-main">
                    <div class="stat-count"><?php echo $total_tamu; ?></div>
                    <div class="stat-label">Total Tamu</div>
                </div>
                <div class="stat-footer"><a href="tabel_tamu.php">View Details</a> <span>&rarr;</span></div>
            </div>
        </div>
        
        <!-- Kotak 2: Hari Ini (Hijau) -->
        <div class="stat-col">
            <div class="stat-box bg-green">
                <div class="stat-main">
                    <div class="stat-count"><?php echo $kunjungan_hari_ini; ?></div>
                    <div class="stat-label">Hari Ini</div>
                </div>
                <div class="stat-footer"><a href="tabel_tamu.php">View Details</a> <span>&rarr;</span></div>
            </div>
        </div>
        
        <!-- Kotak 3: Instansi (Merah) -->
        <div class="stat-col">
            <div class="stat-box bg-red">
                <div class="stat-main">
                    <div class="stat-count"><?php echo $instansi_unik; ?></div>
                    <div class="stat-label">Instansi</div>
                </div>
                <div class="stat-footer"><a href="tabel_tamu.php">View Details</a> <span>&rarr;</span></div>
            </div>
        </div>
        
        <!-- Kotak 4: Sistem Aktif (Oranye) -->
        <div class="stat-col">
            <div class="stat-box bg-orange">
                <div class="stat-main">
                    <div class="stat-count">100%</div>
                    <div class="stat-label">SISTEM AKTIF</div>
                </div>
                <div class="stat-footer"><a href="#">View Details</a> <span>&rarr;</span></div>
            </div>
        </div>

    </div> <!-- Penutup stat-row -->

</div> <!-- Penutup Wadah Putih Utama yang dipindahkan dari baris 128 kemarin -->

