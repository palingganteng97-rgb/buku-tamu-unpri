<?php
session_start();

// 1. Proteksi Halaman Login
if (!isset($_SESSION['login'])) { 
    header("Location: login.php"); 
    exit(); 
}

// 2. Koneksi ke Database Anda
$koneksi = mysqli_connect("localhost", "root", "Slebew234", "db_buku_tamu");

// 3. Mengambil Data Lama Berdasarkan ID dari URL
$id = intval($_GET['id']);
$result = mysqli_query($koneksi, "SELECT * FROM data_instansi WHERE id = $id");
$data = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan, kembalikan ke tabel utama
if (!$data) {
    header("Location: data_instansi.php");
    exit();
}

// Cek apakah kategori lama termasuk dalam opsi default, jika tidak berarti itu kategori kustom "Lainnya"
$opsi_default = ["Kementerian Negara", "Lembaga Tinggi Negara", "Pemerintah Daerah", "Keamanan & Pertahanan", "Korporasi Negara (BUMN/BUMD)", "Pendidikan & Umum"];
$is_kustom = !in_array($data['kategori'], $opsi_default);

// 4. Proses Update Data Saat Tombol Diklik
if (isset($_POST['update'])) {
    $kategori_pilihan = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    
    // Jika memilih "Lainnya", ambil data dari input text manual
    if ($kategori_pilihan == "Lainnya") {
        $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori_lainnya']);
    } else {
        $kategori = $kategori_pilihan;
    }
    
    $nama_instansi = mysqli_real_escape_string($koneksi, $_POST['nama_instansi']);

    $query = "UPDATE data_instansi SET kategori='$kategori', nama_instansi='$nama_instansi' WHERE id=$id";
    if (mysqli_query($koneksi, $query)) {
        header("Location: data_instansi.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashUI - Edit Instansi</title>
    <style>
        body { background-color: #6342ff; font-family: Arial, sans-serif; margin: 0; padding: 20px 0; }
        .info-text { color: white; text-align: center; font-size: 14px; margin-bottom: 20px; font-weight: 500; }
        
        .container { width: 100%; max-width: 720px; margin-right: auto; margin-left: auto; padding-right: 15px; padding-left: 15px; }
        .form-card { background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 40px; border: none; margin-top: 20px; }
        
        .form-label { font-size: 14px; font-weight: bold; color: #333; margin-bottom: 8px; display: block; }
        .text-danger { color: #dc3545 !important; }
        .form-control, .form-select { font-size: 14px; padding: 10px 15px; border: 1px solid #ced4da; border-radius: 6px; color: #495057; display: block; width: 100%; box-sizing: border-box; }
        .form-control::placeholder { color: #adb5bd; }
        .mb-4 { margin-bottom: 1.5rem !important; }
        
        .button-group-flex { display: flex; justify-content: space-between; align-items: center; margin-top: 40px; }
        .btn-kembali { background-color: white; color: #6c757d; border: 1px solid #ced4da; padding: 10px 25px; font-size: 14px; font-weight: 500; border-radius: 6px; text-decoration: none; display: inline-block; }
        .btn-kembali:hover { background-color: #f8f9fa; color: #333; }
        .btn-kirim { background-color: #6342ff; color: white; border: none; padding: 10px 30px; font-size: 14px; font-weight: bold; border-radius: 6px; cursor: pointer; }
        .btn-kirim:hover { background-color: #4f30e6; color: white; }
    </style>
</head>
<body>

    <div class="info-text">Silakan edit data instansi Anda dengan benar</div>

    <div class="container">
        <div class="form-card">
            
            <form action="" method="post">
                
                <!-- 1. Pilihan Dropdown Kategori -->
                <div class="mb-4">
                    <label class="form-label">Klasifikasi Lembaga / Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" id="kategori_select" class="form-select" onchange="toggleKategoriLainnya()" required>
                        <option value="Kementerian Negara" <?= ($data['kategori'] == 'Kementerian Negara') ? 'selected' : ''; ?>>Kementerian Negara</option>
                        <option value="Lembaga Tinggi Negara" <?= ($data['kategori'] == 'Lembaga Tinggi Negara') ? 'selected' : ''; ?>>Lembaga Tinggi Negara</option>
                        <option value="Pemerintah Daerah" <?= ($data['kategori'] == 'Pemerintah Daerah') ? 'selected' : ''; ?>>Pemerintah Daerah</option>
                        <option value="Keamanan & Pertahanan" <?= ($data['kategori'] == 'Keamanan & Pertahanan') ? 'selected' : ''; ?>>Keamanan & Pertahanan</option>
                        <option value="Korporasi Negara (BUMN/BUMD)" <?= ($data['kategori'] == 'Korporasi Negara (BUMN/BUMD)') ? 'selected' : ''; ?>>Korporasi Negara (BUMN/BUMD)</option>
                        <option value="Pendidikan & Umum" <?= ($data['kategori'] == 'Pendidikan & Umum') ? 'selected' : ''; ?>>Pendidikan & Umum</option>
                        <!-- Opsi Lainnya Otomatis Terpilih Jika Kategori Berupa Teks Kustom -->
                        <option value="Lainnya" <?= $is_kustom ? 'selected' : ''; ?>>Lainnya</option>
                    </select>
                </div>

                <!-- 2. Input Manual Kategori (Otomatis Muncul jika memilih 'Lainnya' atau berisi data kustom sebelumnya) -->
                <div class="mb-4" id="input_kategori_lainnya" style="display: <?= $is_kustom ? 'block' : 'none'; ?>;">
                    <label class="form-label">Tuliskan Klasifikasi / Kategori Baru <span class="text-danger">*</span></label>
                    <input type="text" name="kategori_lainnya" id="field_kategori_lainnya" class="form-control" placeholder="Contoh: Organisasi Swasta / Lembaga Internasional" value="<?= $is_kustom ? htmlspecialchars($data['kategori']) : ''; ?>">
                </div>

                <!-- 3. Input Teks Nama Instansi -->
                <div class="mb-4">
                    <label class="form-label">Nama Instansi / Perusahaan Resmi <span class="text-danger">*</span></label>
                    <input type="text" name="nama_instansi" class="form-control" value="<?= htmlspecialchars($data['nama_instansi']); ?>" required placeholder="Masukkan nama instansi resmi">
                </div>

                <!-- 4. Kelompok Tombol Bawah -->
                <div class="button-group-flex">
                    <a href="data_instansi.php" class="btn-kembali">Kembali</a>
                    <button type="submit" name="update" class="btn btn-kirim">Kirim Data</button>
                </div>

            </form>

        </div>
    </div>

    <!-- JavaScript Dinamis untuk Mengontrol Input Manual Opsi Lainnya -->
    <script>
        function toggleKategoriLainnya() {
            var selectBox = document.getElementById("kategori_select");
            var inputDiv = document.getElementById("input_kategori_lainnya");
            var inputField = document.getElementById("field_kategori_lainnya");
            
            if (selectBox.value === "Lainnya") {
                inputDiv.style.display = "block";
                inputField.setAttribute("required", "required");
            } else {
                inputDiv.style.display = "none";
                inputField.removeAttribute("required");
                inputField.value = "";
            }
        }
        
        // Jalankan fungsi sekali saat halaman dimuat untuk mendeteksi data kustom bawaan database
        window.onload = function() {
            var selectBox = document.getElementById("kategori_select");
            var inputField = document.getElementById("field_kategori_lainnya");
            if (selectBox.value === "Lainnya") {
                inputField.setAttribute("required", "required");
            }
        };
    </script>

</body>
</html>
