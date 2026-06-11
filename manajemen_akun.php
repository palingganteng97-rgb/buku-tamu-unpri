<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Proteksi Halaman: Jika belum login, tendang ke login.php
if (!isset($_SESSION['login']) && !isset($_SESSION['admin_user'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke Database
$host     = "localhost";
$username = "root";
$password = "Slebew234"; // Sesuai kata sandi MySQL laptop Anda
$database = "db_buku_tamu";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$error = "";
$sukses = "";
$user_aktif = $_SESSION['admin_user'];

// 1. PROSES SUBMIT: GANTI NAMA & PASSWORD
if (isset($_POST['update_profile'])) {
    $nama_baru     = mysqli_real_escape_string($koneksi, trim($_POST['username_baru']));
    $password_lama = mysqli_real_escape_string($koneksi, $_POST['password_lama']);
    $password_baru = mysqli_real_escape_string($koneksi, $_POST['password_baru']);

    if (empty($nama_baru)) {
        $error = "Nama pengguna baru tidak boleh kosong.";
    } else {
                // Cek validasi password lama ke database user
$cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$user_aktif' AND password='$password_lama'");
        
        if (mysqli_num_rows($cek_user) === 0) {
            $error = "Kata sandi lama yang Anda masukkan salah.";
        } else {
            if (!empty($password_baru)) {
                // Jika password baru diisi, update Nama dan Password sekaligus
$query_update = "UPDATE users SET username='$nama_baru', password='$password_baru' WHERE username='$user_aktif'";
            } else {
                // Jika password baru kosong, hanya update Nama saja
$query_update = "UPDATE users SET username='$nama_baru' WHERE username='$user_aktif'";
            }
            if (mysqli_query($koneksi, $query_update)) {
                $_SESSION['admin_user'] = $nama_baru; // Perbarui session aktif
                $user_aktif = $nama_baru;
                $sukses = "Profil akun berhasil diperbarui!";
            } else {
                $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
            }
        }
    }
}

// 2. PROSES SUBMIT: GANTI AKUN (SWITCH ACCOUNT)
if (isset($_POST['switch_account'])) {
    $switch_username = mysqli_real_escape_string($koneksi, $_POST['switch_username']);
    $switch_password = mysqli_real_escape_string($koneksi, $_POST['switch_password']);

$cek_switch = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$switch_username' AND password='$switch_password'");
    
    if (mysqli_num_rows($cek_switch) === 1) {
        // Ganti data session aktif dengan data akun yang baru dimasukkan
        $_SESSION['login'] = true;
        $_SESSION['admin_user'] = $switch_username;
        header("Location: tampilkan.php"); // Alihkan langsung ke dashboard utama
        exit();
    } else {
        $error = "Gagal beralih akun. Username atau Password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Akun - Buku Tamu</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; }
        .header-nav { background-color: #3b5998; color: white; padding: 15px 20px; font-size: 16px; font-weight: 600; text-align: center; }
        .dashboard-layout { display: flex; min-height: calc(100vh - 52px); }
        .sidebar-menu { width: 240px; background-color: #2c3e50; padding-top: 10px; display: flex; flex-direction: column; justify-content: space-between; }
        .sidebar-menu a { display: block; color: #bdc3c7; padding: 15px 20px; text-decoration: none; font-size: 14px; font-weight: 600; border-left: 4px solid transparent; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: #1a252f; color: white; border-left: 4px solid #3498db; }
        .logout-btn { color: #e74c3c !important; margin-bottom: 20px; }
        .main-container { flex: 1; padding: 30px; }
        .white-card { background: #fff; padding: 25px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .form-grid { display: flex; gap: 30px; flex-wrap: wrap; }
        .form-box { flex: 1; min-width: 300px; background: #fafafa; border: 1px solid #eef0f3; padding: 20px; border-radius: 6px; }
        .form-title { font-size: 16px; font-weight: 600; color: #2c3e50; margin-top: 0; margin-bottom: 15px; border-bottom: 2px solid #3498db; padding-bottom: 5px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px; color: #555; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; outline: none; }
        .form-control:focus { border-color: #3498db; }
        .btn-submit { background-color: #3498db; color: white; border: none; padding: 10px 20px; font-weight: 600; border-radius: 4px; cursor: pointer; width: 100%; font-size: 14px; }
        .btn-submit:hover { background-color: #2980b9; }
        .btn-switch { background-color: #2ecc71; }
        .btn-switch:hover { background-color: #27ae60; }
        .alert { padding: 12px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; font-weight: 600; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

    <div class="header-nav">
                Buku Tamu RIS Kendal
</div>

    <div class="dashboard-layout">
        
        <!-- Sidebar Menu Tetap Konsisten -->
        <div class="sidebar-menu">
            <div>
                <a href="tampilkan.php">DASHBOARD</a>
                <a href="tabel_tamu.php">TABEL TAMU</a>
                <a href="data_instansi.php">DATA INSTANSI</a>
                <a href="tambah.php">TAMBAH TAMU</a>
                <a href="manajemen_akun.php" class="active">MANAJEMEN AKUN</a>
            </div>
            <a href="logout.php" class="logout-btn">LOGOUT</a>
        </div>

        <!-- Kontainer Utama -->
        <div class="main-container">
            <div class="white-card">
                <h2 style="margin-top: 0; margin-bottom: 5px; font-weight: 600; color: #333;">MANAJEMEN AKUN</h2>
                <p style="color: #777; margin-bottom: 25px; font-size: 14px;">Ubah identitas profil admin atau beralih ke akun login admin lainnya</p>

                <!-- Notifikasi Status -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error; ?></div>
                <?php endif; ?>
                <?php if (!empty($sukses)): ?>
                    <div class="alert alert-success"><?= $sukses; ?></div>
                <?php endif; ?>

                <div class="form-grid">
                    
                    <!-- FORM KIRI: GANTI NAMA & PASSWORD -->
                    <div class="form-box">
                        <h3 class="form-title">Ubah Profil Admin Aktif</h3>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label>Nama Pengguna (Username) Saat Ini</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user_aktif); ?>" disabled style="background-color: #e9ecef;">
                            </div>
                            <div class="form-group">
                                <label>Nama Pengguna (Username) Baru</label>
                                <input type="text" class="form-control" name="username_baru" value="<?= htmlspecialchars($user_aktif); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Kata Sandi Lama <span>*</span></label>
                                <input type="password" class="form-control" name="password_lama" placeholder="Masukkan kata sandi saat ini" required>
                            </div>
                            <div class="form-group">
                                <label>Kata Sandi Baru (Kosongkan jika tidak diganti)</label>
                                <input type="password" class="form-control" name="password_baru" placeholder="Masukkan kata sandi baru">
                            </div>
                            <button type="submit" name="update_profile" class="btn-submit">Simpan Perubahan</button>
                        </form>
                    </div>

                    <!-- FORM KANAN: GANTI/BERALIH AKUN (SWITCH ACCOUNT) -->
                    <div class="form-box">
                        <h3 class="form-title">Ganti/Beralih Akun Login</h3>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label>Username Akun Lain</label>
                                <input type="text" class="form-control" name="switch_username" placeholder="Masukkan username akun tujuan" required>
                            </div>
                            <div class="form-group">
                                <label>Kata Sandi Akun Tujuan</label>
                                <input type="password" class="form-control" name="switch_password" placeholder="Masukkan password akun tujuan" required>
                            </div>
                            <button type="submit" name="switch_account" class="btn-submit btn-switch">Beralih & Masuk Akun</button>
