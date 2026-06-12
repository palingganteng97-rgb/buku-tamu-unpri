<?php
session_start();
$host = "localhost";
$username = "root";
$password = "Slebew234";
$database = "db_buku_tamu";

mysqli_report(MYSQLI_REPORT_OFF);
$koneksi = @mysqli_connect($host, $username, $password, $database);
$error = "";

if (isset($_POST['login'])) {
    if (!$koneksi) {
        $error = "Gagal terhubung ke database!";
    } else {
        $user = mysqli_real_escape_string($koneksi, $_POST['username']);
        $pass = $_POST['password'];

        $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$user'");

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($pass, $row['password'])) {
                $_SESSION['login'] = true;
                $_SESSION['admin_user'] = $row['username'];
                header("Location: tampilkan.php");
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - AMU</title>
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
        .forgot-link { text-align: right; margin-top: 6px; margin-bottom: 24px; }
        .forgot-link a { font-size: 12px; color: #8F8C9F; text-decoration: none; }
        .btn-submit { width: 100%; padding: 12px; background-color: #6C5DD3; color: #FFFFFF; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .alert-error { background-color: #FF4A4A; color: white; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; }
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
            <h2 class="title">Welcome Back</h2>
            <p class="subtitle">New around here? <a href="register.php" class="link">Create an account</a></p>
            
            <?php if(!empty($error)): ?>
                <div class="alert-error"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="forgot-link">
                    <a href="lupa_password.php">Forgot password?</a>
                </div>
                <button type="submit" name="login" class="btn-submit">Log In</button>
            </form>
        </div>
    </div>
</body>
</html>
