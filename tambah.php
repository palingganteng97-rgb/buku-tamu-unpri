<?php
// 1. Pengaturan Session & Proteksi Login
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// 2. Koneksi ke Database Anda
$host     = "localhost";
$username = "root";
$password = "Slebew234"; // Sesuai password MySQL laptop Anda
$database = "db_buku_tamu";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 3. Ambil data instansi untuk pilihan Dropdown (Anti-Looping)
$sql_instansi = "SELECT * FROM data_instansi ORDER BY nama_instansi ASC";
$query_opsi_instansi = mysqli_query($koneksi, $sql_instansi);

$error = "";

// 4. Proses ketika tombol submit ditekan
if (isset($_POST['submit'])) {
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email      = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);
    $keperluan  = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    
    $instansi_pilihan = mysqli_real_escape_string($koneksi, $_POST['instansi']);
    if ($instansi_pilihan === "Lainnya") {
        $instansi = mysqli_real_escape_string($koneksi, $_POST['instansi_lainnya']);
        if (!empty($instansi)) {
            $cek_instansi = mysqli_query($koneksi, "SELECT * FROM data_instansi WHERE nama_instansi='$instansi'");
            if (mysqli_num_rows($cek_instansi) == 0) {
                mysqli_query($koneksi, "INSERT INTO data_instansi (nama_instansi) VALUES ('$instansi')");
            }
        }
    } else {
        $instansi = $instansi_pilihan;
    }

    if (empty($nama) || empty($no_telepon) || empty($instansi) || empty($keperluan)) {
        $error = "Silakan isi semua kolom yang wajib diisi!";
    } else {
        $sql_insert = "INSERT INTO tamu (nama, email, no_telepon, instansi, keperluan) 
                       VALUES ('$nama', '$email', '$no_telepon', '$instansi', '$keperluan')";
        
        if (mysqli_query($koneksi, $sql_insert)) {
            header("Location: tabel_tamu.php");
            exit();
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu RSI Kendal - Tambah Tamu</title>
    <style>
        * { box-sizing: border-box; }
        body { background-color: #5d3fd3; font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 0; }
        .banner { text-align: center; color: white; padding: 25px 0; font-size: 18px; font-weight: 500; }
        .container { width: 100%; max-width: 680px; margin: 0 auto 50px auto; padding: 0 15px; }
        .form-card { background: white; border-radius: 10px; padding: 40px; box-shadow: 0 5px 20px rgba(0,0,0,0.15); }
        .form-group { margin-bottom: 22px; }
        .form-group label { display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 15px; }
        .form-group label span { color: #dc3545; }
        .form-input { width: 100%; padding: 12px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 15px; color: #333; outline: none; }
        .form-input:focus { border-color: #5d3fd3; }
        textarea.form-input { resize: vertical; font-family: inherit; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; border: 1px solid #f5c6cb; }
        .btn-group { display: flex; justify-content: space-between; margin-top: 35px; }
        .btn { padding: 12px 28px; font-size: 15px; font-weight: 600; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; }
        .btn-back { background-color: #f8f9fa; color: #495057; border: 1px solid #ced4da; }
        .btn-submit { background-color: #5d3fd3; color: white; border: none; }
    </style>
</head>
<body>

    <div class="banner">
        Silakan isi data kunjungan Anda dengan benar
    </div>

    <div class="container">
        <div class="form-card">
            
            <?php if (!empty($error)): ?>
                <div class="alert-error">
                    <?= $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                
                <div class="form-group">
                    <label for="nama">Nama Lengkap <span>*</span></label>
                    <input type="text" class="form-input" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" class="form-input" id="email" name="email" placeholder="contoh@email.com">
                </div>

                <div class="form-group">
                    <label for="no_telepon">No. Telepon / WhatsApp <span>*</span></label>
                    <input type="text" class="form-input" id="no_telepon" name="no_telepon" placeholder="08123xxxx" required>
                </div>

                <div class="form-group">
                    <label for="instansi_select">Instansi / Perusahaan <span>*</span></label>
                    <select class="form-input" id="instansi_select" name="instansi" onchange="toggleInstansiLainnya()" required>
                        <option value="" disabled selected>-- Cari atau Pilih Instansi Anda --</option>
                        <?php if ($query_opsi_instansi): ?>
                            <?php while($row_instansi = mysqli_fetch_assoc($query_opsi_instansi)): ?>
                                <option value="<?= htmlspecialchars($row_instansi['nama_instansi']); ?>">
                                    <?= htmlspecialchars($row_instansi['nama_instansi']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                        <option value="Lainnya">-- Instansi Lainnya (Ketik Manual) --</option>
                    </select>
                </div>

                <div class="form-group" id="field_instansi_lainnya" style="display: none;">
                    <label for="input_instansi_lainnya">Tulis Nama Instansi Baru <span>*</span></label>
                    <input type="text" class="form-input" id="input_instansi_lainnya" name="instansi_lainnya" placeholder="Ketik nama instansi Anda di sini">
                </div>

                <div class="form-group">
                    <label for="keperluan">Keperluan Kunjungan <span>*</span></label>
                    <textarea class="form-input" id="keperluan" name="keperluan" rows="4" placeholder="Tuliskan tujuan atau keperluan Anda..." required></textarea>
                </div>

                <div class="btn-group">
                    <a href="tabel_tamu.php" class="btn btn-back">Kembali</a>
                    <button type="submit" name="submit" class="btn btn-submit">Kirim Data</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function toggleInstansiLainnya() {
            var selectBox = document.getElementById("instansi_select");
            var inputDiv = document.getElementById("field_instansi_lainnya");
            var inputField = document.getElementById("input_instansi_lainnya");

            if (selectBox.value === "Lainnya") {
                inputDiv.style.display = "block";
                inputField.setAttribute("required", "required");
                inputField.focus();
            } else {
                inputDiv.style.display = "none";
                inputField.removeAttribute("required");
                inputField.value = "";
            }
        }
    </script>
</body>
</html>
