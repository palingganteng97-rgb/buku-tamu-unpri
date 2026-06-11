<?php
session_start();

// 1. Proteksi Halaman Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// 2. Koneksi ke Database Anda
$host     = "localhost";
$username = "root";
$password = "Slebew234";
$database = "db_buku_tamu";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Inisialisasi variabel error agar kosong saat halaman pertama kali dibuka
$error = "";

// 3. Proses ketika tombol submit ditekan
if (isset($_POST['submit'])) {
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email      = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);
    $keperluan  = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    
    // Logika menangkap pilihan instansi dropdown atau ketik manual
    $instansi_pilihan = mysqli_real_escape_string($koneksi, $_POST['instansi']);
    if ($instansi_pilihan == "Lainnya") {
        $instansi = mysqli_real_escape_string($koneksi, $_POST['instansi_lainnya']);
    } else {
        $instansi = $instansi_pilihan;
    }

    // Query insert data ke tabel tamu
    $sql = "INSERT INTO tamu (nama, email, no_telepon, instansi, keperluan) 
            VALUES ('$nama', '$email', '$no_telepon', '$instansi', '$keperluan')";
    
    $query_sukses = mysqli_query($koneksi, $sql);

    if ($query_sukses) {
        // Jika sukses, langsung alihkan ke halaman tabel tamu
        header("Location: tabel_tamu.php");
        exit();
    } else {
        // Jika gagal, isi variabel error dengan pesan kegagalan sistem
        $error = mysqli_error($koneksi);
    }
}

// Ambil daftar instansi dari database untuk dropdown opsi pilihan
$query_opsi_instansi = mysqli_query($koneksi, "SELECT * FROM data_instansi ORDER BY nama_instansi ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashUI - Tambah Tamu</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
        <style>
        body { background-color: #6342ff; font-family: Arial, sans-serif; margin: 0; padding: 20px 0; }
        .info-text { color: white; text-align: center; font-size: 14px; margin-bottom: 20px; font-weight: 500; }
        .form-card { background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 40px; border: none; }
        .form-label { font-size: 14px; font-weight: bold; color: #333; margin-bottom: 8px; }
        .text-danger { color: #dc3545 !important; }
        .form-control, .form-select { font-size: 14px; padding: 10px 15px; border: 1px solid #ced4da; border-radius: 6px; color: #495057; display: block; width: 100%; box-sizing: border-box; }
        .form-control::placeholder { color: #adb5bd; }
        .button-group-flex { display: flex; justify-content: space-between; align-items: center; margin-top: 40px; }
        .btn-kembali { background-color: white; color: #6c757d; border: 1px solid #ced4da; padding: 10px 25px; font-size: 14px; font-weight: 500; border-radius: 6px; text-decoration: none; }
        .btn-kembali:hover { background-color: #f8f9fa; color: #333; }
        .btn-kirim { background-color: #6342ff; color: white; border: none; padding: 10px 30px; font-size: 14px; font-weight: bold; border-radius: 6px; }
        .btn-kirim:hover { background-color: #4f30e6; color: white; }

        /* Kode Perbaikan Tata Letak Grid Offline */
        .container { width: 100%; max-width: 720px; margin-right: auto; margin-left: auto; padding-right: 15px; padding-left: 15px; }
        .row { display: flex; flex-wrap: wrap; margin-right: -15px; margin-left: -15px; }
        .col-md-6 { flex: 0 0 50%; max-width: 50%; padding-right: 15px; padding-left: 15px; }
        .mb-4 { margin-bottom: 1.5rem !important; }
        textarea.form-control { width: 100%; box-sizing: border-box; }
    </style>

</head>
<body>

    <div class="info-text">Silakan isi data kunjungan Anda dengan benar</div>

    <div class="container" style="max-width: 750px;">
        <div class="form-card">
            
            <!-- ALERT DINAMIS: Hanya muncul jika proses kueri database gagal -->
            <?php if ($error != "") : ?>
                <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 25px; font-weight: bold; font-size: 14px; border: 1px solid #f5c6cb;">
                    ⚠️ Gagal menyimpan data: <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form action="" method="post">
                
                <!-- Input Nama -->
                <div class="mb-4">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>

                <!-- Input Email & No Telepon Sejajar -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-control" placeholder="contoh@email.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon / WhatsApp</label>
                        <input type="text" name="no_telepon" class="form-control" placeholder="08123xxxx">
                    </div>
                </div>

                <!-- Pilihan Dropdown Instansi Dinamis dari Database -->
                <div class="mb-4">
                    <label class="form-label">Instansi / Perusahaan <span class="text-danger">*</span></label>
                    <select name="instansi" id="instansi_select" class="form-select" onchange="toggleInstansiLainnya()" required>
                        <option value="" disabled selected>-- Cari atau Pilih Instansi Anda --</option>
                        <?php while($row_instansi = mysqli_fetch_assoc($query_opsi_instansi)): ?>
                            <option value="<?= htmlspecialchars($row_instansi['nama_instansi']); ?>"><?= htmlspecialchars($row_instansi['nama_instansi']); ?></option>
                        <?php endwhile; ?>
                        <option value="Lainnya">-- Instansi Lainnya (Ketik Manual) --</option>
                    </select>
                </div>

                <!-- Input Manual Instansi (Otomatis Muncul jika memilih opsi 'Lainnya') -->
                <div class="mb-4" id="input_instansi_lainnya" style="display: none;">
                    <label class="form-label">Tuliskan Nama Instansi Anda <span class="text-danger">*</span></label>
                    <input type="text" name="instansi_lainnya" id="field_instansi_lainnya" class="form-control" placeholder="Masukkan nama instansi/perusahaan secara lengkap">
                </div>

                <!-- Input Keperluan Kunjungan -->
                <div class="mb-4">
                    <label class="form-label">Keperluan Kunjungan <span class="text-danger">*</span></label>
                    <textarea name="keperluan" class="form-control" rows="4" placeholder="Tuliskan tujuan atau keperluan Anda..." required></textarea>
                </div>

                <!-- Tombol Aksi Bawah Sejajar -->
                <div class="button-group-flex">
                    <a href="tampilkan.php" class="btn-kembali">Kembali</a>
                    <button type="submit" name="submit" class="btn btn-kirim">Kirim Data</button>
                </div>

            </form>

        </div>
    </div>

    <!-- JavaScript Pendukung untuk Opsi Pilihan Instansi 'Lainnya' -->
    <script>
        function toggleInstansiLainnya() {
            var selectBox = document.getElementById("instansi_select");
            var inputDiv = document.getElementById("input_instansi_lainnya");
            var inputField = document.getElementById("field_instansi_lainnya");
            
            if (selectBox.value === "Lainnya") {
                inputDiv.style.display = "block";
                inputField.setAttribute("required", "required");
            } else {
                inputDiv.style.display = "none";
                inputField.removeAttribute("required");
                inputField.value = "";
            }
        }
    </script>

</body>
</html>
