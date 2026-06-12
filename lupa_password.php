<?php
// 1. KONEKSI KE DATABASE
$host = "localhost";
$username = "root";
$password = "Slebew234";
$database = "db_buku_tamu";

mysqli_report(MYSQLI_REPORT_OFF);
$koneksi = @mysqli_connect($host, $username, $password, $database);

$message = "";
$status = "";

// 2. LOGIKA PROSES SUBMIT (Hanya berjalan jika tombol Send Reset Link diklik)
if (isset($_POST['reset'])) {
    if (!$koneksi) {
        $message = "Gagal terhubung ke database!";
        $status = "error";
    } else {
        // Mengambil data input yang sudah disinkronkan
$user_or_email = mysqli_real_escape_string($koneksi, $_POST['username_email']);
        
        // Memeriksa keberadaan pengguna di database users
        $query = "SELECT * FROM users WHERE username = '$user_or_email'";
        $result = mysqli_query($koneksi, $query);
        
        if (mysqli_num_rows($result) === 1) {
            $message = "Link reset sandi telah dikirim ke akun Anda!";
            $status = "success";
        } else {
            $message = "Username atau Email tidak ditemukan!";
            $status = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - AMU</title>
    <style>
        /* Pengaturan Layout Global */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, sans-serif; }
        body { background-color: #151321; color: #ffffff; min-height: 100vh; display: flex; justify-content: center; align-items: center; }
        .container { background-color: #1F1D2C; border-radius: 12px; box-shadow: 0 30px 60px rgba(0,0,0,0.4); display: flex; width: 850px; min-height: 550px; overflow: hidden; }
        
        /* PANEL KIRI: Desain Gambar Gunung */
        .left-panel { 
            width: 46%; 
            background-image: linear-gradient(to top, rgba(17, 11, 22, 0.95) 10%, rgba(26, 21, 44, 0.4) 60%, transparent), url('gunung.png'); 
            background-size: cover; 
            background-position: center; 
            position: relative; 
            padding: 42px; 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
            overflow: hidden; 
        }
        .logo { font-size: 20px; font-weight: 700; letter-spacing: 3px; color: #FFFFFF; position: relative; z-index: 10; }
        .dune-graphic { width: 100%; height: auto; position: relative; }
        .headline-section { position: relative; z-index: 10; margin-bottom: 5px; margin-top: auto; }
        .headline { font-size: 22px; font-weight: 500; line-height: 1.35; color: #E4E3E9; text-align: center; }
        
        /* PANEL KANAN: Formulir Reset */
        .right-panel { width: 54%; padding: 48px 52px; display: flex; flex-direction: column; justify-content: center; }
        .title { font-size: 28px; font-weight: 500; margin-bottom: 6px; color: #FFFFFF; }
        .subtitle { color: #8F8C9F; font-size: 12px; margin-bottom: 28px; }
        .link { color: #A4A1B5; text-decoration: none; border-bottom: 1px solid #A4A1B5; }
        .form-group { margin-bottom: 14px; }
        input[type="text"] { width: 100%; padding: 12px 16px; background-color: #262436; border: 1px solid #323048; border-radius: 6px; color: #ffffff; font-size: 14px; outline: none; }
        input[type="text"]:focus { border-color: #6C5DD3; }
        .btn-submit { width: 100%; padding: 12px; background-color: #6C5DD3; color: #FFFFFF; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; margin-top: 10px; font-size: 14px; }
        .btn-submit:hover { background-color: #5b4eb8; }
        
        /* Notifikasi Alert */
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 13px; text-align: center; font-weight: 500; }
        .alert-success { background-color: #2ECC71; color: white; }
        .alert-error { background-color: #E74C3C; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <!-- SEKTOR VISUAL KIRI -->
        <div class="left-panel">
            <div class="logo">AMU</div>
            <div class="dune-graphic">
                <div class="headline-section">
                    <h1 class="headline">Capturing Moments,<br>Creating Memories</h1>
                </div>
            </div>
        </div>

        <!-- SEKTOR FORMULIR KANAN -->
        <div class="right-panel">
            <h2 class="title">Reset Password</h2>
            <p class="subtitle">Remember your password? <a href="login.php" class="link">Log In</a></p>
            
            <!-- Menampilkan Kotak Pesan Hanya Saat Ada Aksi Post -->
            <?php if(!empty($message)): ?>
                <div class="alert alert-<?= $status; ?>"><?= $message; ?></div>
            <?php endif; ?>

            <form method="POST" action="lupa_password.php">
                <div class="form-group">
                    <input type="text" name="username_email" placeholder="Enter your Username or Email" required>
                </div>
                <button type="submit" name="reset" class="btn-submit">Send Reset Link</button>
            </form>
        </div>
    </div>
</body>
</html>
