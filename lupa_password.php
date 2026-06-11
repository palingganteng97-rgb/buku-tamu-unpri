<?php
session_start();
$host     = "localhost";
$username = "root";         
$password = "Slebew234";     
$database = "db_buku_tamu"; 

$koneksi = mysqli_connect($host, $username, $password, $database);

$sukses = "";
$error = "";

if (isset($_POST['reset'])) {
    $user = mysqli_real_escape_string($koneksi, $_POST['username']);
    $pass_baru = $_POST['password_baru'];
    $confirm_pass = $_POST['confirm_password'];

    // 1. Cek apakah username terdaftar
    $cek_user = mysqli_query($koneksi, "SELECT username FROM users WHERE username = '$user'");
    
    if (mysqli_num_rows($cek_user) === 0) {
        $error = "Username tidak ditemukan!";
    } elseif ($pass_baru !== $confirm_pass) {
        $error = "Konfirmasi password baru tidak cocok!";
    } else {
        // 2. Enkripsi password baru
        $password_aman = password_hash($pass_baru, PASSWORD_DEFAULT);

        // 3. Update password di database
        $sql = "UPDATE users SET password = '$password_aman' WHERE username = '$user'";
        if (mysqli_query($koneksi, $sql)) {
            $sukses = "Password berhasil diperbarui! Silakan <a href='login.php' style='color: #1e88e5; font-weight: bold;'>Login</a>";
        } else {
            $error = "Gagal memperbarui password: " . mysqli_error($koneksi);
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
    <title>Lupa Password - Buku Tamu</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
    <style>
        body, html { margin: 0; padding: 0; height: 100vh; font-family: 'Arial', sans-serif; overflow: hidden; }
        .wrapper { display: flex; height: 100vh; width: 100vw; }
        
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
            content: ''; position: absolute; top: 12px; left: 50%; transform: translateX(-50%);
            width: 60px; height: 6px; background-color: #2b303a; border-radius: 3px; z-index: 10;
        }
        /* Gambar Masuk Sempurna Tanpa Terpotong */
        .phone-mockup img { 
            width: 100%; 
            height: 100%; 
            object-fit: contain; 
            display: block; 
            border-radius: 12px; 
        }

        /* Sisi Kanan Form Biru */
        .right-side { flex: 1; background-color: #1e88e5; display: flex; align-items: center; justify-content: center; padding: 40px; }
        .form-box { width: 100%; max-width: 360px; text-align: center; color: #ffffff; }
        .form-box h2 { font-weight: bold; letter-spacing: 1.5px; margin-bottom: 25px; text-transform: uppercase; }
        .form-control-capsule { background-color: #ffffff !important; border: none; border-radius: 50px !important; padding: 12px 25px; font-size: 15px; color: #333333; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.08); margin-bottom: 15px; }
        .form-control-capsule::placeholder { color: #aaaaaa; text-align: center; }
        .btn-submit-capsule { background-color: #4caf50; color: white; border: none; border-radius: 50px; padding: 12px; width: 100%; font-weight: bold; font-size: 15px; text-transform: uppercase; box-shadow: 0 4px 12px rgba(76,175,80,0.3); margin-top: 10px; }
        .back-link { color: rgba(255,255,255,0.8); text-decoration: none; font-size: 13px; display: inline-block; margin-top: 20px; }
        @media (max-width: 768px) { .left-side { display: none; } }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- SISI KIRI: MEMANGGIL GAMBAR PATRICK LAIN KALI JANGAN LUPA -->
    <div class="left-side">
        <div class="phone-mockup">
            <!-- Jika saat download format file Anda adalah JFIF biasa, ganti patrick_lupa.jpg.jfif menjadi patrick_lupa.jpg -->
            <img src="jangan_lupa.png." alt="Lain Kali Jangan Lupa">
        </div>
    </div>

    <!-- SISI KANAN -->
    <div class="right-side">
        <div class="form-box">
            <h2>Reset Password</h2>

            <?php if($error): ?>
                <div class="alert alert-danger py-2 small border-0 mb-3" style="border-radius: 50px; background-color: #ffebee; color: #c62828;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if($sukses): ?>
                <div class="alert alert-success py-2 small border-0 mb-3" style="border-radius: 50px; background-color: #e8f5e9; color: #2e7d32;">
                    <?php echo $sukses; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <input type="text" name="username" class="form-control form-control-capsule" placeholder="Masukkan Username Anda" required autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="password" name="password_baru" class="form-control form-control-capsule" placeholder="Password Baru" required>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" class="form-control form-control-capsule" placeholder="Konfirmasi Password Baru" required>
                </div>
                <button type="submit" name="reset" class="btn btn-submit-capsule">Update Password</button>
            </form>

            <a href="login.php" class="back-link" style="color: white;">Kembali ke halaman <strong style="text-decoration: underline;">Login</strong></a>
        </div>
    </div>
</div>

</body>
</html>
