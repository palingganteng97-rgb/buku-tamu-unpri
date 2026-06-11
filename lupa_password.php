<?php
$host = "localhost";
$username = "root";
$password = "Slebew234";
$database = "db_buku_tamu";

$koneksi = @mysqli_connect($host, $username, $password, $database);
$error = "";
$success = "";

if (isset($_POST['reset'])) {
    if (!$koneksi) {
        $error = "Gagal terhubung ke database!";
    } else {
        $user = mysqli_real_escape_string($koneksi, $_POST['username']);
        $pass_baru = $_POST['password_baru'];
        
        $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$user'");
        if (mysqli_num_rows($cek_user) === 1) {
            $pass_hashed = password_hash($pass_baru, PASSWORD_BCRYPT);
            $query = "UPDATE users SET password = '$pass_hashed' WHERE username = '$user'";
            if (mysqli_query($koneksi, $query)) {
                $success = "Password diganti! Silakan kembali ke halaman login.";
            } else {
                $error = "Terjadi kesalahan sistem saat memperbarui password.";
            }
        } else {
            $error = "Username/Nama Lengkap tidak ditemukan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - AMU</title>
    <style>
        /* Pengaturan Layout Global Agar Presisi Sesuai Halaman Register & Login */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background-color: #151321; color: #ffffff; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 24px; }
        
        /* Container Utama */
        .container { background-color: #1F1D2C; border-radius: 12px; box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6); display: flex; max-width: 900px; width: 100%; min-height: 560px; overflow: hidden; }
        
        /* PANEL KIRI: Desain Bukit Pasir Murni CSS (Bebas Error Gambar) */
        .left-panel { width: 46%; background: linear-gradient(135deg, #2D254E, #141220); padding: 42px; display: flex; flex-direction: column; justify-content: space-between; position: relative; }
        .logo-section { display: flex; justify-content: space-between; align-items: center; z-index: 2; }
        .logo { font-size: 20px; font-weight: 700; letter-spacing: 3px; color: #FFFFFF; font-family: monospace; }
        
        .dune-graphic { width: 100%; height: 210px; margin-top: 15px; position: relative; overflow: hidden; border-radius: 8px; background: linear-gradient(180deg, rgba(45,37,78,0.2) 0%, rgba(20,18,32,0.6) 100%); }
        .dune-1 { position: absolute; bottom: -30px; left: -20px; width: 120%; height: 140px; background-color: #1C1A29; border-radius: 50% 50% 0 0; transform: rotate(-5deg); opacity: 0.8; }
        .dune-2 { position: absolute; bottom: -60px; right: -30px; width: 130%; height: 150px; background-color: #161423; border-radius: 60% 40% 0 0; transform: rotate(4deg); }
        
        .headline-section { z-index: 2; margin-bottom: 5px; }
        .headline { font-size: 22px; font-weight: 500; line-height: 1.35; color: #E4E3E9; text-align: center; margin-bottom: 24px; letter-spacing: 0.3px; }
        
        /* Indikator Garis Bawah Kiri */
        .indicator-group { display: flex; justify-content: center; gap: 6px; }
        .ind { width: 18px; height: 2px; background-color: #3C394E; border-radius: 1px; }
        .ind.active { width: 26px; background-color: #FFFFFF; }

        /* PANEL KANAN: Form Pengisian */
        .right-panel { width: 54%; padding: 48px 52px; display: flex; flex-direction: column; justify-content: center; }
        @media (max-width: 768px) { .left-panel { display: none; } .right-panel { width: 100%; padding: 35px; } }
        
        .title { font-size: 28px; font-weight: 500; margin-bottom: 6px; color: #FFFFFF; letter-spacing: 0.5px; }
        .subtitle { color: #8F8C9F; font-size: 12px; margin-bottom: 28px; }
        
        .form-group { margin-bottom: 16px; position: relative; }
        
        input[type="text"], input[type="password"] { width: 100%; background-color: #262436; border: 1px solid #36344A; border-radius: 6px; padding: 12px 14px; font-size: 13px; color: #FFFFFF; outline: none; }
        input::placeholder { color: #5D5A70; }
        input:focus { border-color: #6C5DD3; background-color: #29273B; }
        
        /* Tombol Ungu Indigo */
        .btn-submit { width: 100%; background-color: #6C5DD3; color: #FFFFFF; border: none; border-radius: 6px; padding: 12px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.2s; margin-bottom: 24px; }
        .btn-submit:hover { background-color: #5B4EB8; }
        
        .back-to-login { text-align: center; }
        .back-to-login a { font-size: 13px; color: #8F8C9F; text-decoration: none; transition: color 0.2s; }
        .back-to-login a:hover { color: #ffffff; text-decoration: underline; }

        .alert-error { background-color: rgba(127, 29, 29, 0.4); color: #FCA5A5; font-size: 12px; padding: 10px; border-radius: 6px; margin-bottom: 16px; border: 1px solid #991B1B; }
        .alert-success { background-color: rgba(6, 78, 59, 0.4); color: #A7F3D0; font-size: 12px; padding: 12px; border-radius: 6px; margin-bottom: 16px; border: 1px solid #065F46; }
    </style>
</head>
<body>

    <div class="container">
        <!-- SEKTOR KIRI: Identitas Visual Seragam -->
        <div class="left-panel">
            <div class="logo-section">
                <div class="logo">ΛMU</div>
            </div>
            
            <!-- Perbaikan Grafik Pasir Lokal Mandiri -->
            <div class="dune-graphic">
                <div class="dune-1"></div>
                <div class="dune-2"></div>
            </div>
            
            <div class="headline-section">
                <h1 class="headline">Capturing Moments,<br>Creating Memories</h1>
                <div class="indicator-group">
                    <span class="ind"></span>
                    <span class="ind"></span>
                    <span class="ind active"></span>
                </div>
            </div>
        </div>

        <!-- SEKTOR KANAN: Form Reset Password -->
        <div class="right-panel">
            <h2 class="title">Reset Password</h2>
            <p class="subtitle">Ubah kata sandi akun buku tamu Anda.</p>
            
            <?php if($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>
            <?php if($success): ?><div class="alert-success"><?= $success ?></div><?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Confirm Username / Full Name" required>
                </div>
                
                <div class="form-group">
                    <input type="password" name="password_baru" placeholder="Enter new password" required>
                </div>
                
                <button type="submit" name="reset" class="btn-submit">Update Password</button>
                
                <div class="back-to-login">
                    <a href="login.php">&larr; Back to Log in</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
