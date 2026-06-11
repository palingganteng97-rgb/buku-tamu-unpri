<?php
session_start();
$host = "localhost";
$username = "root";
$password = "Slebew234"; 
$database = "db_buku_tamu";

mysqli_report(MYSQLI_REPORT_OFF);
$koneksi = @mysqli_connect($host, $username, $password, $database);
$error = "";
$success = "";

if (isset($_POST['register'])) {
    if (!$koneksi) {
        $error = "Gagal terhubung ke database!";
    } else {
        $first_name = mysqli_real_escape_string($koneksi, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($koneksi, $_POST['last_name']);
        $user = trim($first_name . " " . $last_name);
        $pass = $_POST['password'];
        
        $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$user'");
        if (mysqli_num_rows($cek_user) > 0) {
            $error = "Nama/Username sudah terdaftar!";
        } else {
            $pass_hashed = password_hash($pass, PASSWORD_BCRYPT);
            $query = "INSERT INTO users (username, password) VALUES ('$user', '$pass_hashed')";
            if (mysqli_query($koneksi, $query)) {
                $success = "Registrasi sukses! Silakan beralih ke halaman Login.";
            } else {
                $error = "Gagal menyimpan data ke database.";
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
        /* Pengaturan Layout Global agar Presisi Sesuai Gambar */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background-color: #151321; color: #ffffff; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 24px; }
        
        /* Container Utama dengan Efek Rounded Tipis */
        .container { background-color: #1F1D2C; border-radius: 12px; box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6); display: flex; max-width: 900px; width: 100%; min-height: 560px; overflow: hidden; }
        
        /* PANEL KIRI: Desain Bukit Pasir Malam Hari */
        .left-panel { width: 46%; background: linear-gradient(135deg, #2D254E, #141220); padding: 42px; display: flex; flex-direction: column; justify-content: space-between; position: relative; }
        .logo-section { display: flex; justify-content: space-between; align-items: center; z-index: 2; }
        .logo { font-size: 20px; font-weight: 700; letter-spacing: 3px; color: #FFFFFF; font-family: monospace; }
        .back-link { font-size: 11px; color: #A09EAE; text-decoration: none; display: flex; align-items: center; gap: 4px; }
        .back-link:hover { color: #ffffff; }
        
        /* Grafik Bukit Pasir Buatan Menggunakan CSS Murni */
        .dune-graphic { width: 100%; height: 210px; margin-top: 15px; position: relative; overflow: hidden; border-radius: 8px; background: linear-gradient(180deg, rgba(45,37,78,0.2) 0%, rgba(20,18,32,0.6) 100%); }
        .dune-1 { position: absolute; bottom: -30px; left: -20px; width: 120%; height: 140px; background-color: #1C1A29; border-radius: 50% 50% 0 0; transform: rotate(-5deg); opacity: 0.8; }
        .dune-2 { position: absolute; bottom: -60px; right: -30px; width: 130%; height: 150px; background-color: #161423; border-radius: 60% 40% 0 0; transform: rotate(4deg); }
        
        .headline-section { z-index: 2; margin-bottom: 5px; }
        .headline { font-size: 22px; font-weight: 500; line-height: 1.35; color: #E4E3E9; text-align: center; margin-bottom: 24px; letter-spacing: 0.3px; }
        
        /* Indikator Garis Tiga Batang di Bawah Kiri */
        .indicator-group { display: flex; justify-content: center; gap: 6px; }
        .ind { width: 18px; height: 2px; background-color: #3C394E; border-radius: 1px; }
        .ind.active { width: 26px; background-color: #FFFFFF; }

        /* PANEL KANAN: Form Pengisian */
        .right-panel { width: 54%; padding: 48px 52px; display: flex; flex-direction: column; justify-content: center; }
        @media (max-width: 768px) { .left-panel { display: none; } .right-panel { width: 100%; padding: 35px; } }
        
        .title { font-size: 28px; font-weight: 500; margin-bottom: 6px; color: #FFFFFF; letter-spacing: 0.5px; }
        .subtitle { color: #8F8C9F; font-size: 12px; margin-bottom: 28px; }
        .link { color: #A4A1B5; text-decoration: none; border-bottom: 1px solid #A4A1B5; padding-bottom: 1px; margin-left: 3px; }
        .link:hover { color: #ffffff; border-color: #ffffff; }
        
        /* Input Grid untuk Kolom Nama Terpisah */
        .name-row { display: flex; gap: 14px; margin-bottom: 12px; }
        .form-group { margin-bottom: 12px; position: relative; }
        
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; background-color: #262436; border: 1px solid #36344A; border-radius: 6px; padding: 11px 14px; font-size: 13px; color: #FFFFFF; outline: none; }
        input::placeholder { color: #5D5A70; }
        input:focus { border-color: #5D54A4; background-color: #29273B; }
        
        /* Ikon Mata pada Password */
        .eye-icon { position: absolute; right: 14px; top: 13px; width: 14px; height: 14px; opacity: 0.4; cursor: pointer; }
        .eye-icon:hover { opacity: 0.8; }
        
        /* Syarat Ketentuan Berwarna Biru Indigo Tipis */
        .checkbox-group { display: flex; align-items: center; gap: 8px; margin-top: 14px; margin-bottom: 24px; font-size: 11px; color: #8F8C9F; }
        .checkbox-group input { accent-color: #6C5DD3; width: 13px; height: 13px; cursor: pointer; border-radius: 3px; }
        .checkbox-group a { color: #7F74C7; text-decoration: none; border-bottom: 1px solid #7F74C7; }
        .checkbox-group a:hover { color: #9A91D8; border-color: #9A91D8; }
        
        /* Tombol Ungu Elegan Sesuai Gambar */
        .btn-submit { width: 100%; background-color: #6C5DD3; color: #FFFFFF; border: none; border-radius: 6px; padding: 11px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background-color: #5B4EB8; }
        
        /* Garis Tipis Divider Tengah */
        .divider { display: flex; align-items: center; text-align: center; color: #5D5A70; font-size: 9px; margin: 20px 0; text-transform: lowercase; letter-spacing: 0.5px; }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid #2B293D; }
        .divider:not(:empty)::before { margin-right: .8em; }
        .divider:not(:empty)::after { margin-left: .8em; }
        
        /* Baris Tombol Sosial Ganda */
        .social-group { display: flex; gap: 14px; }
        .btn-social { flex: 1; background: transparent; border: 1px solid #36344A; border-radius: 6px; padding: 9px; color: #FFFFFF; font-size: 12px; font-weight: 400; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 8px; }
        .btn-social:hover { background-color: #262436; }
        .icon-svg { width: 14px; height: 14px; }

        .alert-error { background-color: rgba(127, 29, 29, 0.4); color: #FCA5A5; font-size: 12px; padding: 10px; border-radius: 6px; margin-bottom: 12px; border: 1px solid #991B1B; }
        .alert-success { background-color: rgba(6, 78, 59, 0.4); color: #A7F3D0; font-size: 12px; padding: 10px; border-radius: 6px; margin-bottom: 12px; border: 1px solid #065F46; }
    </style>
</head>
<body>

    <div class="container">
        <!-- SEKTOR KIRI: Identitas Visual -->
        <div class="left-panel">
            <div class="logo-section">
                <div class="logo">ΛMU</div>
                <a href="#" class="back-link">Back to website &rarr;</a>
            </div>
            
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

        <!-- SEKTOR KANAN: Blok Form Isian Akun -->
        <div class="right-panel">
            <h2 class="title">Create an account</h2>
            <p class="subtitle">Already have an account?<a href="login.php" class="link">Log in</a></p>
            
            <?php if($error): ?><div class="alert-error"><?= $error ?></div><?php endif; ?>
            <?php if($success): ?><div class="alert-success"><?= $success ?></div><?php endif; ?>

            <!-- ========================================== -->
<!-- 1. BLOK FORM UTAMA (KHUSUS UNTUK REGISTER) -->
<!-- ========================================== -->
<form method="POST" action="">
    <div class="name-row">
        <input type="text" name="first_name" placeholder="Fletcher" required>
        <input type="text" name="last_name" placeholder="Last name">
    </div>
    
    <div class="form-group">
        <input type="email" placeholder="Email" required>
    </div>
    
    <div class="form-group">
        <input type="password" name="password" placeholder="Enter your password" required>
        <!-- Ikon Mata -->
        <svg class="eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
    </div>
    
    <div class="checkbox-group">
        <input type="checkbox" id="terms" required checked>
        <label for="terms">I agree to the <a href="#">Terms & Conditions</a></label>
    </div>
    
    <!-- Tombol Submit Form -->
    <button type="submit" name="register" class="btn-submit">Create account</button>
</form>


<!-- ========================================== -->
<!-- 2. BLOK SOSIAL LOG IN (TERPISAH DI LUAR FORM) -->
<!-- ========================================== -->
<div class="divider">Or register with</div>

<div class="social-group">
    <!-- Tombol Google (Murni Eksternal) -->
    <button type="button" class="btn-social" onclick="window.location.href='https://google.com'">
        <svg class="icon-svg" viewBox="0 0 24 24"><path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.7l3.1-3.1C17.3 1.8 14.8 1 12 1 7.3 1 3.4 3.7 1.6 7.7l3.8 3C6.3 7.7 8.9 5 12 5z"/><path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.7-.2-2.3H12v4.5h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5 3.7-8.8z"/><path fill="#FBBC05" d="M5.4 14.8c-.2-.7-.4-1.5-.4-2.3s.2-1.6.4-2.3L1.6 7.2C.6 9.2 0 11.5 0 14s.6 4.8 1.6 6.8l3.8-3z"/><path fill="#34A853" d="M12 23c3.2 0 6-1.1 8-2.9l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3.1 0-5.7-2.7-6.6-5.7l-3.8 3C3.4 20.3 7.3 23 12 23z"/></svg>
        Google
    </button>
    
    <!-- Tombol Apple (Murni Eksternal) -->
    <button type="button" class="btn-social" onclick="window.location.href='https://apple.com'">
        <svg class="icon-svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.17c.66-.81 1.11-1.93.99-3.06-1 .04-2.14.67-2.86 1.51-.62.73-1.17 1.87-1.02 2.98 1.12.09 2.23-.62 2.89-1.43z"/></svg>
        Apple
    </button>
</div>

