<?php
// 1. Pengaturan Session & Proteksi Login
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// 2. Koneksi ke Database Anda
$host     = "localhost";
$username = "root";
$password = "Slebew234"; // Sesuai password MySQL laptop Anda
$database = "db_buku_tamu";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 3. Logika Fitur Pencarian Data (Search)
$keyword = "";
$kondisi = "";

if (isset($_POST['cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, trim($_POST['keyword']));
    // Filter pencarian berdasarkan Nama Tamu, Email, atau nama Instansi
    $kondisi = " WHERE nama LIKE '%$keyword%' OR email LIKE '%$keyword%' OR instansi LIKE '%$keyword%'";
}

// 4. Query Eksekusi Utama Menampilkan Data Tamu terbaru
$sql_tamu = "SELECT * FROM tamu" . $kondisi . " ORDER BY id DESC";
$query_tamu = mysqli_query($koneksi, $sql_tamu);

if (!$query_tamu) {
    die("Query tabel gagal: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu RSI Kendal - Data Pengunjung</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; }
        .header-nav { background-color: #3b5998; color: white; padding: 15px 20px; font-size: 16px; font-weight: 600; text-align: center; }
        .dashboard-layout { display: flex; min-height: calc(100vh - 52px); background-color: #f4f6f9; }
        .sidebar-menu { width: 240px; background-color: #2c3e50; padding-top: 10px; display: flex; flex-direction: column; min-height: calc(100vh - 52px); justify-content: space-between; }
        .sidebar-menu a { display: block; color: #bdc3c7; padding: 15px 20px; text-decoration: none; font-size: 14px; font-weight: 600; border-left: 4px solid transparent; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: #1a252f; color: white; border-left: 4px solid #3498db; }
        .logout-btn { color: #e74c3c !important; margin-bottom: 20px; }
        .main-container { flex: 1; padding: 30px; }
        .white-card { background: #fff; padding: 25px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        
        /* Gaya Desain Tabel */
        .table-responsive { width: 100%; overflow-x: auto; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        th { background-color: #f8f9fa; color: #333; font-weight: 600; padding: 12px; border: 1px solid #dee2e6; text-align: center; }
        td { padding: 12px; border: 1px solid #dee2e6; color: #495057; }
        tr:nth-child(even) { background-color: #fdfdfd; }
        
        /* Gaya Desain Tombol Aksi */
        .btn-action { padding: 6px 12px; border: none; border-radius: 4px; color: white; cursor: pointer; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-block; }
        .btn-edit { background-color: #00a8ff; margin-right: 5px; }
        .btn-delete { background-color: #e84118; }
        .btn-edit:hover { background-color: #0097e6; }
        .btn-delete:hover { background-color: #c23616; }
    </style>
</head>
<body>

    <div class="header-nav">
        Buku Tamu RSI Kendal
    </div>

    <div class="dashboard-layout">
        
        <!-- Sidebar Menu Berurutan Rapi -->
        <div class="sidebar-menu">
            <div>
                <a href="tampilkan.php">DASHBOARD</a>
                <a href="tabel_tamu.php" class="active">TABEL TAMU</a>
                <a href="data_instansi.php">DATA INSTANSI</a>
                <a href="manajemen_akun.php">MANAJEMEN AKUN</a>
            </div>
            <div style="padding-bottom: 20px;">
                <a href="logout.php" class="logout-btn" style="color: #e74c3c; font-weight: 600; text-decoration: none; display: block; padding: 15px 20px;">LOGOUT</a>
            </div>
        </div>

        <!-- Kontainer Utama -->
        <div class="main-container">
            <div class="white-card">
                
                <h2 style="margin-top: 0; margin-bottom: 5px; font-weight: 600; color: #333;">DATA PENGUNJUNG</h2>
                <p style="color: #777; margin-bottom: 25px; font-size: 14px;">Log Riwayat Aktivitas Buku Tamu</p>
                
                <!-- BARIS KONTROL: FITUR CARI (KIRI) & TOMBOL CREATE (KANAN) -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                    
                    <!-- Form Fitur Pencarian Data -->
                    <form action="" method="POST" style="display: flex; gap: 8px; width: 100%; max-width: 400px; margin: 0;">
                        <input type="text" name="keyword" placeholder="Cari nama, email, atau instansi..." value="<?php echo htmlspecialchars($keyword); ?>" style="flex: 1; padding: 10px 14px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; outline: none;">
                        <button type="submit" name="cari" style="background-color: #3498db; color: white; border: none; padding: 10px 20px; font-weight: 600; border-radius: 4px; cursor: pointer; font-size: 14px;">Cari</button>
                        
                        <?php if (!empty($keyword)): ?>
                            <a href="tabel_tamu.php" style="background-color: #e74c3c; color: white; text-decoration: none; padding: 10px 16px; font-weight: 600; border-radius: 4px; font-size: 14px; display: inline-flex; align-items: center;">Reset</a>
                        <?php endif; ?>
                    </form>

                    <!-- Tombol Tambah Tamu Baru (Create) -->
                    <a href="tambah.php" style="background-color: #2ecc71; color: white; text-decoration: none; padding: 10px 22px; font-weight: 600; border-radius: 4px; font-size: 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: background 0.2s;">
                        + Tambah Tamu Baru
                        </a>
                        <!-- Masukkan ini di sebelah atau di bawah tombol "+ Tambah Tamu Baru" -->
                    <button onclick="eksporExcel()" style="background-color: #27ae60; color: white; border: none; padding: 10px 22px; font-weight: 600; border-radius: 4px; font-size: 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
    📊 Ekspor ke Excel
                    </button>

                    

                </div>

                <!-- Rendering Baris Tabel Pengunjung -->
                <div class="table-responsive">
                        <table id="tabel_pengunjung">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th>Nama Tamu</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Instansi / Lembaga</th>
                                <th>Keperluan</th>
                                <th style="width: 150px;">Waktu Kunjungan</th>
                                <th style="width: 120px;" data-exclude="true">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($query_tamu) > 0): 
                                while ($row = mysqli_fetch_assoc($query_tamu)): 
                            ?>
                                <tr>
                                    <td style="text-align: center;">
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['no_telepon']); ?></td>
                                    <td><?php echo htmlspecialchars($row['instansi']); ?></td>
                                    <td><?php echo htmlspecialchars($row['keperluan']); ?></td>
                                    <td style="text-align: center;">
                                        <?php 
                                        // Ganti 'tgl_kunjungan' dengan nama kolom tanggal asli jika di database Anda namanya berbeda
                                        echo isset($row['tgl_kunjungan']) ? htmlspecialchars($row['tgl_kunjungan']) : date('Y-m-d H:i:s'); 
                                        ?>
                                    </td>
                                    <td style="text-align: center;" data-exclude="true">
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit">✏️</a>
                                        <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data tamu ini?')">🗑️</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; color: #999; padding: 20px;">Data tamu tidak ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
