<?php
$host = "localhost";
$username = "root";
$password = "Slebew234";
$database = "db_buku_tamu";

mysqli_report(MYSQLI_REPORT_OFF);
$koneksi = @mysqli_connect($host, $username, $password, $database);
$message = "";
$status = "";

if (isset($_POST['register'])) {
    if (!$koneksi) {
        $message = "Gagal terhubung ke database!";
        $status = "error";
    } else {
        $user = mysqli_real_escape_string($koneksi, $_POST['username']);
        $pass = $_POST['password'];
        
        $cek_user = mysqli_query($koneksi, "SELECT username FROM users WHERE username = '$user'");
        if (mysqli_num_rows($cek_user) > 0) {
            $message = "Username sudah terdaftar!";
            $status = "error";
        } else {
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, password) VALUES ('$user', '$hashed_password')";
            
            if (mysqli_query($koneksi, $query)) {
                $message = "Akun berhasil dibuat! Silakan login.";
                $status = "success";
            } else {
                $message = "Gagal mendaftarkan akun.";
                $status = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an account - AMU</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, sans-serif; }
        body { background-color: #151321; color: #ffffff; min-height: 100vh; display: flex; justify-content: center; align-items: center; }
        .container { background-color: #1F1D2C; border-radius: 12px; box-shadow: 0 30px 60px rgba(0,0,0,0.4); display: flex; width: 850px; min-height: 550px; overflow: hidden; }
        
        /* PANEL KIRI: Latar Belakang Gunung */
        .left-panel { width: 46%; background-image: linear-gradient(to top, rgba(17, 11, 22, 0.95) 10%, rgba(26, 21, 44, 0.4) 60%, transparent), url('gunung.png'); background-size: cover; background-position: center; position: relative; padding: 42px; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; }
        .logo { font-size: 20px; font-weight: 700; letter-spacing: 3px; color: #FFFFFF; position: relative; z-index: 10; }
        .dune-graphic { width: 100%; height: auto; position: relative; }
        .headline-section { position: relative; z-index: 10; margin-bottom: 5px; margin-top: auto; }
        .headline { font-size: 22px; font-weight: 500; line-height: 1.35; color: #E4E3E9; text-align: center; }
        
        /* PANEL KANAN: Formulir */
        .right-panel { width: 54%; padding: 48px 52px; display: flex; flex-direction: column; justify-content: center; }
        .title { font-size: 28px; font-weight: 500; margin-bottom: 6px; color: #FFFFFF; }
        .subtitle { color: #8F8C9F; font-size: 12px; margin-bottom: 28px; }
        .link { color: #A4A1B5; text-decoration: none; border-bottom: 1px solid #A4A1B5; }
        .form-group { margin-bottom: 14px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 12px 16px; background-color: #262436; border: 1px solid #323048; border-radius: 6px; color: #ffffff; font-size: 14px; }
        .btn-submit { width: 100%; padding: 12px; background-color: #6C5DD3; color: #FFFFFF; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; margin-top: 10px; }
        .alert { padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; }
        .alert-error { background-color: #FF4A4A; color: white; }
        .alert-success { background-color: #2ECC71; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="logo">AMU</div>
            <div class="dune-graphic">
                <div class="headline-section">
                    <h1 class="headline">Capturing Moments,<br>Creating Memories</h1>
                </div>
            </div>
        </div>
        <div class="right-panel">
            <h2 class="title">Create Account</h2>
            <p class="subtitle">Already have an account? <a href="login.php" class="link">Log In</a></p>
            
            <?php if(!empty($message)): ?>
                <div class="alert alert-<?= $status; ?>"><?= $message; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Choose a Username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Create a Password" required>
                </div>
                <button type="submit" name="register" class="btn-submit">Register</button>
            </form>
        </div>
    </div>
</body>
</html>
