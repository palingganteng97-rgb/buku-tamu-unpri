<?php
session_start();

// 1. Proteksi Halaman Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// 2. Koneksi ke Database Anda
$koneksi = mysqli_connect("localhost", "root", "Slebew234", "db_buku_tamu");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 3. Ambil data dari tabel tamu
$query = mysqli_query($koneksi, "SELECT * FROM tamu ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabel Tamu - Sistem Informasi Buku Tamu</title>
    <style>
        /* CSS Utama Layout Sistem (Seragam dengan halaman lainnya) */
        * { box-sizing: border-box; }
        body { background-color: #f4f6f9; font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .top-navbar { background-color: #2c5e8a; color: white; padding: 15px 20px; font-size: 16px; font-weight: bold; }
        .dashboard-layout { display: flex; min-height: calc(100vh - 54px); }
        
        /* Sidebar Menu */
        .sidebar-menu { width: 220px; background-color: #3e3e3e; flex-shrink: 0; padding-top: 10px; display: flex; flex-direction: column; justify-content: space-between; }
        .menu-top-group { width: 100%; }
        .sidebar-menu a { display: block; color: #dfdfdf; padding: 15px 20px; text-decoration: none; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: #2c5e8a; color: white; }
        .sidebar-menu .logout-link { border-top: 1px solid #4f4f4f; border-bottom: none; color: #ff8888; text-align: left; }
        .sidebar-menu .logout-link:hover { background-color: #d9534f; color: white; }
        
        /* Konten Utama Sebelah Kanan */
        .main-container { flex-grow: 1; padding: 25px; overflow-x: hidden; background-color: #f4f6f9; }
        .table-card { background: white; border-radius: 4px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); padding: 20px; border: 1px solid #dee2e6; }
        .table-header-flex { margin-bottom: 20px; }
        
        /* Desain Garis Tabel Bergaris Rapi dan Rapat */
        .table-responsive { width: 100%; overflow-x: auto; margin-top: 15px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; font-size: 13px; background-color: #fff; }
        .table, .table th, .table td { border: 1px solid #cccccc !important; }
        .table th, .table td { padding: 10px 12px; vertical-align: middle; }
        .table-light { background-color: #f8f9fa; color: #212529; font-weight: bold; }
        
        /* Desain Tombol Ikon Kotak Aksi Meniru Referensi Anda */
        .btn-action-edit { 
            background-color: #0ea5e9; /* Hijau Toska / Biru Cerah */
            color: white; 
            border: none; 
            padding: 6px 10px; 
            border-radius: 6px; 
            font-size: 14px; 
            cursor: pointer; 
            display: inline-block;
            text-decoration: none;
        }
        .btn-action-edit:hover { background-color: #0284c7; }
        
        .btn-action-hapus { 
            background-color: #ef4444; /* Merah */
            color: white; 
            border: none; 
            padding: 6px 10px; 
            border-radius: 6px; 
            font-size: 14px; 
            text-decoration: none; 
            display: inline-block; 
            cursor: pointer; 
        }
        .btn-action-hapus:hover { background-color: #dc2626; }
    </style>
</head>
<body>

    <!-- Navbar Atas -->
    <div class="top-navbar">
        Buku Tamu RIS Kendal
    </div>

    <!-- Layout Utama Dashboard -->
    <div class="dashboard-layout">
        
        <!-- Sidebar Menu Samping Kiri -->
        <div class="sidebar-menu">
            <div class="menu-top-group">
                <a href="tampilkan.php">Dashboard</a>
                <a class="active" href="tabel_tamu.php">Tabel Tamu</a>
                <a href="data_instansi.php">Data Instansi</a>
                <a href="manajemen_akun.php">MANAJEMEN AKUN</a>
            </div>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>

        <!-- Area Konten Utama Kanan -->
        <div class="main-container">
            <div class="table-card">
                
                <div class="table-header-flex">
                    <h4 class="m-0" style="color: #333; font-weight: bold; font-size: 18px;">DATA PENGUNJUNG</h4>
                    <small class="text-muted" style="font-weight: 500;">Log Riwayat Aktivitas Buku Tamu</small>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%; text-align: center;">ID</th>
                                <th style="width: 15%;">Nama Tamu</th>
                                <th style="width: 20%;">Email</th>
                                <th style="width: 12%;">No. Telepon</th>
                                <th style="width: 20%;">Instansi / Lembaga</th>
                                <th style="width: 10%;">Keperluan</th>
                                <th style="width: 13%;">Waktu Kunjungan</th>
                                <th style="width: 10%; text-align: center;">Aksi</th> <!-- Kolom Aksi dirapatkan menjadi 10% -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (mysqli_num_rows($query) > 0) :
                                while ($row = mysqli_fetch_assoc($query)) : 
                            ?>
                            <tr>
                                <td style="text-align: center; font-weight: bold;"><?= htmlspecialchars($row['id']); ?></td>
                                <td style="color: #333; font-weight: 500;"><?= htmlspecialchars($row['nama']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><?= htmlspecialchars($row['no_telepon']); ?></td>
                                <td><?= htmlspecialchars($row['instansi']); ?></td>
                                <td><?= htmlspecialchars($row['keperluan']); ?></td>
<td style="font-size: 12px; color: #555;"><?= htmlspecialchars($row['tanggal_kunjungan']); ?></td>
                                <td style="text-align: center; white-space: nowrap;">
                                    
                                    <!-- Tombol Edit Hubungkan ke file edit.php bawaan Anda dengan Simbol Unicode -->
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn-action-edit me-1">
                                        &#9998;
                                    </a>
                                    
                                    <!-- Tombol Hapus Hubungkan ke file hapus.php bawaan Anda dengan Simbol Unicode -->
                                    <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus riwayat kunjungan tamu ini?')" class="btn-action-hapus">
                                        &#128465;
                                    </a>

                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else :
                                echo "<tr><td colspan='8' style='text-align: center; color: #888; padding: 20px;'>Belum ada riwayat aktivitas buku tamu.</td></tr>";
                            endif; 
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
