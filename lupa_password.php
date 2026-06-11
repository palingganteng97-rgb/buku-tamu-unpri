<?php
session_start();

// 1. PENGATURAN KONEKSI DATABASE
$host     = "localhost";
$username = "root";
$password = "Slebew234"; // Sesuaikan dengan password HeidiSQL Anda
$database = "db_buku_tamu";

// Mematikan error report bawaan agar tidak lemot terlalu lama jika gagal koneksi
mysqli_report(MYSQLI_REPORT_OFF); 
$koneksi = @mysqli_connect($host, $username, $password, $database);

$error = "";
$success = "";

// 2. LOGIKA KETIKA TOMBOL UPDATE PASSWORD DIKLIK
if (isset($_POST['update_password'])) {
    if (!$koneksi) {
        $error = "Gagal terhubung ke database! Periksa MySQL/HeidiSQL Anda.";
    } else {
        $user = mysqli_real_escape_string($koneksi, $_POST['username']);
        $pass_baru = $_POST['password_baru'];
        $konfirmasi_pass = $_POST['konfirmasi_password'];

        if ($pass_baru !== $konfirmasi_pass) {
            $error = "Konfirmasi password baru tidak sesuai!";
        } else {
            // Cek apakah username tersebut terdaftar di database
            $cek_user = mysqli_query($koneksi, "SELECT username FROM users WHERE username = '$user'");
            
            if (mysqli_num_rows($cek_user) === 0) {
                $error = "Username tidak ditemukan!";
            } else {
                // Enkripsi password baru menggunakan bcrypt
                $password_hashed = password_hash($pass_baru, PASSWORD_DEFAULT);

                // Update password ke database
                $query = "UPDATE users SET password = '$password_hashed' WHERE username = '$user'";
                
                if (mysqli_query($koneksi, $query)) {
                    $success = "Password berhasil diperbarui! Silakan login kembali.";
                } else {
                    $error = "Gagal memperbarui password di database!";
                }
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
    <title>Lupa Password - Buku Tamu</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            display: flex;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }
        /* Sisi Kiri (Gambar Patrick) */
        .left-side {
            width: 50%;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .phone-container {
            width: 320px;
            border: 5px solid #2c3e50;
            border-radius: 30px;
            padding: 40px 15px 20px 15px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .phone-container::before {
            content: '';
            position: absolute;
            top: 15px;
            width: 60px;
            height: 6px;
            background-color: #2c3e50;
            border-radius: 3px;
        }
        .phone-container img {
            width: 100%;
            border-radius: 5px;
        }
        /* Sisi Kanan (Form Reset Biru) */
        .right-side {
            width: 50%;
            background-color: #1e88e5;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #ffffff;
            padding: 40px;
        }
        .right-side h1 {
            font-size: 2.2rem;
            letter-spacing: 2px;
            margin-bottom: 30px;
            font-weight: bold;
        }
        form {
            width: 100%;
            max-width: 340px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }
        .input-group {
            width: 100%;
        }
        .input-group input {
            width: 100%;
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            text-align: center;
            color: #333;
            outline: none;
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 25px;
            background-color: #4caf50;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background-color: #43a047;
        }
        .links-container {
            margin-top: 25px;
            text-align: center;
            font-size: 0.85rem;
        }
        .links-container a {
            color: #ffffff;
            text-decoration: underline;
            font-weight: bold;
        }
        .msg-box {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            margin-bottom: 15px;
            max-width: 340px;
            text-align: center;
            font-weight: bold;
        }
        .error-msg {
            background-color: #ffffe0;
            color: #cc0000;
        }
        .success-msg {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
    </style>
</head>
<body>

    <!-- KOLOM KIRI -->
    <div class="left-side">
        <div class="phone-container">
            <!-- Sesuaikan dengan file gambar Patrick jangan lupa Anda -->
            <img src="jangan_lupa.png" alt="Reset Password Image" onerror="this.src='jangan_lupa.jpg'"> 
        </div>
    </div>

    <!-- KOLOM KANAN -->
    <div class="right-side">
        <h1>RESET PASSWORD</h1>

        <!-- Notifikasi Pesan Error / Sukses -->
        <?php if (!empty($error)): ?>
            <div class="msg-box error-msg"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="msg-box success-msg"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="input-group">
                <input type="password" name="password_baru" placeholder="Password Baru" required>
            </div>
            <div class="input-group">
                <input type="password" name="konfirmasi_password" placeholder="Konfirmasi Password Baru" required>
            </div>
            <button type="submit" name="update_password" class="btn-submit">UPDATE PASSWORD</button>
        </form>

        <div class="links-container">
            <p>Kembali ke halaman <a href="login.php">Login</a></p>
        </div>
    </div>

</body>
</html>
