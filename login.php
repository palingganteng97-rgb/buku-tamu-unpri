<?php
session_start();
$host = "localhost";
$username = "root";
$password = "Slebew234"; 
$database = "db_buku_tamu";

mysqli_report(MYSQLI_REPORT_OFF);
$koneksi = @mysqli_connect($host, $username, $password, $database);
$error = "";

// 1. LOGIKA LOGIN MANUAL (FORM INPUT)
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
            $error = "Username tidak terdaftar!";
        }
    }
}

// 2. LOGIKA LOGIN GOOGLE (PENGIRIMAN DATA VIA AJAX FETCH)
if (isset($_GET['google_login'])) {
    $email = mysqli_real_escape_string($koneksi, $_GET['email']);
    $nama = mysqli_real_escape_string($koneksi, $_GET['name']);

    if (!empty($email)) {
        // Cek apakah email/username Google sudah ada di database Anda
        $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$email' OR username = '$nama'");
        
        if (mysqli_num_rows($result) > 0) {
            // Jika sudah terdaftar, langsung buat session login
            $row = mysqli_fetch_assoc($result);
            $_SESSION['login'] = true;
            $_SESSION['admin_user'] = $row['username'];
            echo json_encode(["status" => "success"]);
        } else {
            // Jika belum ada, otomatis daftarkan akun baru ke tabel users
            $pass_random = password_hash(rand(100000,999999), PASSWORD_BCRYPT);
            $query = "INSERT INTO users (username, password) VALUES ('$email', '$pass_random')";
            if (mysqli_query($koneksi, $query)) {
                $_SESSION['login'] = true;
                $_SESSION['admin_user'] = $email;
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error"]);
            }
        }
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - AMU</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background-color: #151321; color: #ffffff; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 24px; }
        .container { background-color: #1F1D2C; border-radius: 12px; box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6); display: flex; max-width: 900px; width: 100%; min-height: 560px; overflow: hidden; }
        .left-panel { width: 46%; background: linear-gradient(135deg, #2D254E, #141220); padding: 42px; display: flex; flex-direction: column; justify-content: space-between; position: relative; }
        .logo { font-size: 20px; font-weight: 700; letter-spacing: 3px; color: #FFFFFF; font-family: monospace; }
        .dune-graphic { width: 100%; height: 210px; margin-top: 15px; position: relative; overflow: hidden; border-radius: 8px; background: linear-gradient(180deg, rgba(45,37,78,0.2) 0%, rgba(20,18,32,0.6) 100%); }
        .dune-1 { position: absolute; bottom: -30px; left: -20px; width: 120%; height: 140px; background-color: #1C1A29; border-radius: 50% 50% 0 0; transform: rotate(-5deg); opacity: 0.8; }
        .dune-2 { position: absolute; bottom: -60px; right: -30px; width: 130%; height: 150px; background-color: #161423; border-radius: 60% 40% 0 0; transform: rotate(4deg); }
        .headline { font-size: 22px; font-weight: 500; line-height: 1.35; color: #E4E3E9; text-align: center; margin-bottom: 24px; }
        .right-panel { width: 54%; padding: 48px 52px; display: flex; flex-direction: column; justify-content: center; }
        .title { font-size: 28px; font-weight: 500; margin-bottom: 6px; color: #FFFFFF; }
        .subtitle { color: #8F8C9F; font-size: 12px; margin-bottom: 28px; }
        .link { color: #A4A1B5; text-decoration: none; border-bottom: 1px solid #A4A1B5; margin-left: 3px; }
        .form-group { margin-bottom: 14px; }
        input[type="text"], input[type="password"] { width: 100%; background-color: #262436; border: 1px solid #36344A; border-radius: 6px; padding: 12px 14px; font-size: 13px; color: #FFFFFF; outline: none; }
        .forgot-link { text-align: right; margin-top: 6px; margin-bottom: 24px; }
        .forgot-link a { font-size: 12px; color: #8F8C9F; text-decoration: none; }
        .btn-submit { width: 100%; background-color: #6C5DD3; color: #FFFFFF; border: none; border-radius: 6px; padding: 12px; font-size: 13px; font-weight: 500; cursor: pointer; }
        .divider { display: flex; align-items: center; text-align: center; color: #5D5A70; font-size: 9px; margin: 20px 0; text-transform: lowercase; letter-spacing: 0.5px; }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid #2B293D; }
        .divider:not(:empty)::before { margin-right: .8em; }
        .divider:not(:empty)::after { margin-left: .8em; }
        .alert-error { background-color: rgba(127, 29, 29, 0.4); color: #FCA5A5; font-size: 12px; padding: 10px; border-radius: 6px; margin-bottom: 12px; border: 1px solid #991B1B; }
        .btn-apple { width: 100%; max-width: 320px; background-color: transparent; border: 1px solid #36344A; border-radius: 4px; padding: 10px; color: #ffffff; font-size: 14px; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 12px; }
        .btn-apple:hover { background-color: #262436; }
    </style>
</head>
<body>

    <div class="container">
        <!-- SEKTOR KIRI -->
        <div class="left-panel">
            <div class="logo">ΛMU</div>
            <div class="dune-graphic"><div class="dune-1"></div><div class="dune-2"></div></div>
            <div><h1 class="headline">Capturing Moments,<br>Creating Memories</h1></div>
        </div>

        <!-- SEKTOR KANAN -->
        <div class="right-panel">
            <h2 class="title">Welcome Back</h2>
            <p class="subtitle">New around here?<a href="register.php" class="link">Create an account</a></p>
            
            <?php if($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>

            <form method="POST" action="">
                <div class="form-group"><input type="text" name="username" placeholder="Username / Full Name" required></div>
                <div class="form-group"><input type="password" name="password" placeholder="Enter your password" required></div>
                <div class="forgot-link"><a href="lupa_password.php">Forgot password?</a></div>
                <button type="submit" name="login" class="btn-submit">Log in</button>
            </form>
            
            <div class="divider">Or log in with</div>
            
            <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                <!-- TOMBOL GOOGLE TERBARU: MURNI TANPA JAVASCRIPT AGAR TIDAK ERROR -->
<button type="button" style="width: 100%; max-width: 320px; background-color: #1a73e8; border: none; border-radius: 4px; padding: 10px; color: white; font-size: 14px; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 10px; font-weight: 500;" 
        onclick="window.location.href='https://google.com'">
    <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24"><path fill="#ffffff" d="M12.24 10.285V13.4h6.86c-.277 1.56-1.602 4.585-6.86 4.585-4.54 0-8.24-3.765-8.24-8.4s3.7-8.4 8.24-8.4c2.58 0 4.307 1.095 5.298 2.045l2.465-2.37C18.435 1.21 15.62 0 12.24 0 5.58 0 0 5.37 0 12s5.58 12 12.24 12c6.96 0 11.57-4.89 11.57-11.79 0-.795-.085-1.4-.195-1.925H12.24z"/></svg>
    Sign in with Google
</button>


                <button type="button" class="btn-apple" onclick="window.location.href='https://apple.com'">
                    <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.17c.66-.81 1.11-1.93.99-3.06-1 .04-2.14.67-2.86 1.51-.62.73-1.17 1.87-1.02 2.98 1.12.09 2.23-.62 2.89-1.43z"/></svg>
                    Sign in with Apple
                </button>
            </div>

        </div>
    </div>

    <script>
        function loginDenganGoogle() {
            const clientId = "123456789-abcdef.apps.googleusercontent.com";
            const redirectUri = "http://localhost:8080/bukutamu/login.php";
            const scope = "email profile";
            const responseType = "token";
            
            const googleAuthUrl =
            `https://accounts.google.com/o/oauth2/v2/auth?client_id=${clientId}` +
                `&redirect_uri=${encodeURIComponent(redirectUri)}` +
                `&response_type=token` +
                `&scope=${encodeURIComponent(scope)}` +
                `&prompt=select_account`;
        }

                // FUNGSI DETEKSI DAN PENANGKAP AKUN GOOGLE (PERBAIKAN KODE)
        window.onload = function() {
            const fragment = window.location.hash;
            if (fragment) {
                const params = new URLSearchParams(fragment.substring(1));
                const accessToken = params.get("access_token");
                
                if (accessToken) {
                    // 1. Mengambil data profil resmi secara aman dari API Google Cloud
                    fetch("https://googleapis.com" + accessToken)
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        
                        // 2. Mengirimkan email dan nama yang dipilih ke sistem PHP di bagian atas
                        const urlBackend = "login.php?google_login=1&email=" + encodeURIComponent(data.email) + "&name=" + encodeURIComponent(data.name);
                        
                        fetch(urlBackend)
                        .then(function(res) { return res.json(); })
                        .then(function(result) {
                            if (result.status === "success") {
                                // 3. Sukses! Pengguna langsung dialihkan masuk ke dashboard buku tamu
                                window.location.href = "tampilkan.php";
                            } else {
                                alert("Gagal menyinkronkan sesi login Google.");
                            }
                        })
                        .catch(function(err) {
                            alert("Terjadi kesalahan sinkronisasi sistem lokal.");
                        });
                        
                    });
                }
            }
        }

