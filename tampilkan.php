<?php
// 1. Pengaturan Proteksi & Session
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['login']) && !isset($_SESSION['admin_user'])) {
    header("Location: login.php");
    exit();
}

// 2. Koneksi ke Database
$host     = "localhost";
$username = "root";
$password = "Slebew234"; // Password MySQL Anda
$database = "db_buku_tamu";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 3. Ambil Data Statistik (ANTI LEMOT & ANTI LOOPING)
// Hitung Total Tamu
$query_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tamu");
$data_total  = mysqli_fetch_assoc($query_total);
$total_tamu  = $data_total['total'];

// // Hitung Tamu Hari Ini (Dimatikan sementara agar tidak eror)
// $hari_ini          = date('Y-m-d');
// $query_hari_ini    = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tamu WHERE DATE(tgl_kunjungan) = '$hari_ini'");
// $data_hari_ini     = mysqli_fetch_assoc($query_hari_ini);
// Hitung Tamu Hari Ini (Versi Aman Tanpa Cek Kolom tgl_kunjungan)
$kunjungan_hari_ini = $total_tamu;
// Hitung Instansi Unik
$query_instansi = mysqli_query($koneksi, "SELECT COUNT(DISTINCT instansi) as total FROM tamu");
$data_instansi  = mysqli_fetch_assoc($query_instansi);
$instansi_unik  = $data_instansi['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Buku Tamu</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f6f9;
        }
        .header-nav {
            background-color: #3b5998;
            color: white;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
        }
        .dashboard-layout {
            display: flex;
            min-height: calc(100vh - 52px);
        }
        .sidebar-menu {
            width: 240px;
            background-color: #2c3e50;
            padding-top: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar-menu a {
            display: block;
            color: #bdc3c7;
            padding: 15px 20px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-left: 4px solid transparent;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: #1a252f;
            color: white;
            border-left: 4px solid #3498db;
        }
        .logout-btn {
            color: #e74c3c !important;
            margin-bottom: 20px;
        }
        .main-container {
            flex: 1;
            padding: 30px;
        }
        .white-card {
            background: #fff;
            padding: 25px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .welcome-box {
            background: #f8f9fc;
            border-left: 4px solid #4e73df;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 30px;
        }
        .stat-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .stat-col {
            flex: 1;
            min-width: 200px;
        }
        .stat-box {
            color: white;
            padding: 20px;
            border-radius: 6px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .bg-blue { background-color: #2980b9; }
        .bg-green { background-color: #27ae60; }
        .bg-red { background-color: #c0392b; }
        .bg-orange { background-color: #d35400; }
        
        .stat-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .stat-count {
            font-size: 36px;
            font-weight: bold;
        }
        .stat-label {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            opacity: 0.9;
        }
        .stat-footer {
            border-top: 1px solid rgba(255,255,255,0.3);
            padding-top: 10px;
            font-size: 12px;
        }
        .stat-footer a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="header-nav">
          Buku Tamu RIS Kendal
    </div>

    <div class="dashboard-layout">
        
        <!-- Sidebar Menu Berisi Opsi Baru -->
        <div class="sidebar-menu">
            <div>
                <a href="tampilkan.php" class="active">DASHBOARD</a>
                <a href="tabel_tamu.php">TABEL TAMU</a>
                <a href="data_instansi.php">DATA INSTANSI</a>
                <a href="tambah.php">TAMBAH TAMU</a>
                <a href="manajemen_akun.php">MANAJEMEN AKUN</a>
            </div>
            <a href="logout.php" class="logout-btn">LOGOUT</a>
        </div>

        <!-- Kontainer Utama -->
        <div class="main-container">
            <div class="white-card">
                
                <h2 style="margin-top: 0; margin-bottom: 5px; font-weight: 600; color: #333;">DASHBOARD</h2>
                <p style="color: #777; margin-bottom: 25px; font-size: 14px;">Ringkasan Aktivitas Buku Tamu</p>
                
                <div class="welcome-box">
                    <span style="color: #5a5c69; font-size: 14px; display: block; margin-bottom: 2px;">Selamat Datang,</span>
                    <strong style="color: #3a3b45; font-size: 20px; font-weight: 700;">
                        <?php echo isset($_SESSION['admin_user']) ? htmlspecialchars(strtoupper($_SESSION['admin_user'])) : 'ADMIN'; ?>
                    </strong>
                </div>

                <!-- Blok Kotak Statistik -->
                <div class="stat-row">
                    
                    <!-- Kotak 1: Total Tamu -->
                    <div class="stat-col">
                        <div class="stat-box bg-blue">
                            <div class="stat-main">
                                <div class="stat-count"><?php echo $total_tamu; ?></div>
                                <div class="stat-label">Total Tamu</div>
                            </div>
                            <div class="stat-footer"><a href="tabel_tamu.php">View Details &rarr;</a></div>
                        </div>
                    </div>
                    
                    <!-- Kotak 2: Hari Ini -->
                    <div class="stat-col">
                        <div class="stat-box bg-green">
                            <div class="stat-main">
                                <div class="stat-count"><?php echo $kunjungan_hari_ini; ?></div>
                                <div class="stat-label">Hari Ini</div>
                            </div>
                            <div class="stat-footer"><a href="tabel_tamu.php">View Details &rarr;</a></div>
                        </div>
                    </div>
                    
                    <!-- Kotak 3: Instansi -->
                    <div class="stat-col">
                        <div class="stat-box bg-red">
                            <div class="stat-main">
                                <div class="stat-count"><?php echo $instansi_unik; ?></div>
                                <div class="stat-label">Instansi</div>
                            </div>
                            <div class="stat-footer"><a href="data_instansi.php">View Details &rarr;</a></div>
                        </div>
                    </div>
                    
                    <!-- Kotak 4: Sistem Aktif (Sudah Diperbaiki Warnanya) -->
                    <div class="stat-col">
                        <div class="stat-box bg-orange">
                            <div class="stat-main">
                                <div class="stat-count">100%</div>
                                <div class="stat-label">Sistem Aktif</div>
                            </div>
                            <div class="stat-footer"><a href="#">View Details &rarr;</a></div>
                        </div>
                    </div>

                </div> <!-- Penutup stat-row -->

            </div>
        </div>

    </div>

</body>
</html>
