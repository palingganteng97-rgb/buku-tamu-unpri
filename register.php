<?php
$host     = "localhost";
$username = "root";         
$password = "Slebew234";     
$database = "db_buku_tamu"; 

$koneksi = mysqli_connect($host, $username, $password, $database);

$error = "";

if (isset($_POST['register'])) {
    $user = mysqli_real_escape_string($koneksi, $_POST['username']);
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($pass !== $confirm_pass) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $password_aman = password_hash($pass, PASSWORD_DEFAULT);

        $cek_user = mysqli_query($koneksi, "SELECT username FROM users WHERE username = '$user'");
        if (mysqli_num_rows($cek_user) > 0) {
            $error = "Username sudah terdaftar!";
        } else {
            $sql = "INSERT INTO users (username, password) VALUES ('$user', '$password_aman')";
            if (mysqli_query($koneksi, $sql)) {
                // JIKA BERHASIL: Tutup koneksi lalu langsung alihkan ke halaman login
                mysqli_close($koneksi);
                header("Location: login.php");
                exit(); 
            } else {
                $error = "Gagal registrasi: " . mysqli_error($koneksi);
            }
        }
    }
}
mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Buku Tamu</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://cloudflare.com" rel="stylesheet">
    
    <style>
        body, html { margin: 0; padding: 0; height: 100vh; font-family: 'Arial', sans-serif; overflow: hidden; }
        .register-wrapper { display: flex; height: 100vh; width: 100vw; }

        /* Sisi Kiri Tempat Mockup HP */
        .left-side { flex: 1; background-color: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; }

        /* Desain Frame Handphone Yang Proporsional */
        .phone-mockup { 
            width: 300px; 
            height: 450px; 
            border: 8px solid #2b303a; 
            border-radius: 28px; 
            background-color: #f8f9fa; 
            overflow: hidden; 
            position: relative; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.15); 
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }
        /* Speaker Atas Handphone */
        .phone-mockup::before {
            content: '';
            position: absolute;
            top: 12px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 6px;
            background-color: #2b303a;
            border-radius: 3px;
            z-index: 10;
        }
        /* Gambar Mengisi Layar HP Tanpa Terpotong */
        .phone-mockup img { 
            width: 100%; 
            height: 100%; 
            object-fit: contain; 
            display: block; 
            border-radius: 12px;
        }

        /* Sisi Kanan Form Biru */
        .right-side { flex: 1; background-color: #1e88e5; display: flex; align-items: center; justify-content: center; padding: 40px; }
        .register-form-box { width: 100%; max-width: 360px; text-align: center; color: #ffffff; }
        .register-form-box h2 { font-weight: bold; letter-spacing: 1.5px; margin-bottom: 25px; text-transform: uppercase; }
        .form-control-capsule { background-color: #ffffff !important; border: none; border-radius: 50px !important; padding: 12px 25px; font-size: 15px; color: #333333; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.08); margin-bottom: 15px; }
        .form-control-capsule::placeholder { color: #aaaaaa; text-align: center; }
        .btn-submit-capsule { background-color: #4caf50; color: white; border: none; border-radius: 50px; padding: 12px; width: 100%; font-weight: bold; font-size: 15px; text-transform: uppercase; box-shadow: 0 4px 12px rgba(76,175,80,0.3); margin-top: 10px; }
        .login-link { color: rgba(255,255,255,0.8); text-decoration: none; font-size: 13px; display: inline-block; margin-top: 20px; }
        @media (max-width: 768px) { .left-side { display: none; } }
    </style>
</head>
<body>

<div class="register-wrapper">
    
    <!-- SISI KIRI: GAMBAR PATRICK DI DALAM LAYAR HP -->
    <div class="left-side">
        <div class="phone-mockup">
            <!-- Menyesuaikan dengan nama file gambar Anda yang sukses muncul -->
            <img src="daftar.jfif" alt="Ayo Daftar Sekarang">
        </div>
    </div>

    <!-- SISI KANAN -->
    <div class="right-side">
        <div class="register-form-box">
            <h2>Register</h2>

            <?php if($error): ?>
                <div class="alert alert-danger py-2 small border-0 mb-3 text-center" style="border-radius: 50px; background-color: #ffebee; color: #c62828;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <input type="text" name="username" class="form-control form-control-capsule" placeholder="Username" required autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-capsule" placeholder="Password Baru" required>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" class="form-control form-control-capsule" placeholder="Konfirmasi Password" required>
                </div>
                <button type="submit" name="register" class="btn btn-submit-capsule">Submit</button>
            </form>

            <a href="login.php" class="login-link" style="color:white;">Sudah memiliki akun admin? <strong style="text-decoration:underline;">Login disini</strong></a>
        </div>
    </div>

</div>

</body>
</html>
