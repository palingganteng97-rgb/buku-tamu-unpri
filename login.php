<?php
session_start();

// 1. PENGATURAN KONEKSI DATABASE
$host     = "localhost";
$username = "root";
$password = "Slebew234"; // Sesuai database HeidiSQL Anda
$database = "db_buku_tamu";

// Mengatur timeout koneksi agar tidak lemot terlalu lama jika gagal
mysqli_report(MYSQLI_REPORT_OFF); 
$koneksi = @mysqli_connect($host, $username, $password, $database);

$error = "";

// 2. LOGIKA KETIKA TOMBOL SUBMIT DIKLIK
if (isset($_POST['login'])) {
    if (!$koneksi) {
        // Jika database bermasalah, langsung tampilkan error alih-alih loading lemot
        $error = "Gagal terhubung ke database! Periksa HeidiSQL/MySQL Anda.";
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
    <title>Login - Buku Tamu</title>
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
        /* Sisi Kanan (Form Biru) */
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
            margin-top: 20px;
            text-align: center;
            font-size: 0.85rem;
        }
        .links-container a {
            color: #ffffff;
            text-decoration: underline;
        }
        .error-msg {
            background-color: #ffffe0;
            color: #cc0000;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            margin-bottom: 15px;
            max-width: 340px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- KOLOM KIRI -->
    <div class="left-side">
        <div class="phone-container">
            <!-- Pastikan nama file gambar patrick sesuai di folder Anda -->
            <img src="patrick.jpg" alt="Login Image"> 
        </div>
    </div>

    <!-- KOLOM KANAN -->
    <div class="right-side">
        <h1>WELCOME</h1>

        <!-- Notifikasi Error jika koneksi database/login gagal -->
        <?php if (!empty($error)): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn-submit">SUBMIT</button>
        </form>

        <div class="links-container">
            <p><a href="lupa_password.php">Lupa Password? Klik disini</a></p>
            <p style="margin-top: 5px; color: #e0e0e0;">Belum memiliki akses admin? <a href="register.php">Daftar disini</a></p>
        </div>
    </div>

</body>
</html>
