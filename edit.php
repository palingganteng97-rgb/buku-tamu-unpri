<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
// ... kode konfigurasi database di bawahnya tetap dilanjutkan

$host     = "localhost";
$username = "root";         
$password = "Slebew234";     
$database = "db_buku_tamu"; 

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 1. Ambil data lama berdasarkan ID untuk ditampilkan di form
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM tamu WHERE id = '$id'");
    $data = mysqli_fetch_assoc($query);
    
    if (!$data) {
        die("Data tidak ditemukan.");
    }
} else {
    header("Location: tampilkan.php");
    exit();
}

// 2. Proses update data saat tombol simpan perubahan ditekan
if (isset($_POST['update'])) {
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email      = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);
    
    // Logika menangkap pilihan instansi dropdown atau ketik manual jika pilih 'Lainnya'
    $instansi_pilihan = mysqli_real_escape_string($koneksi, $_POST['instansi']);
    if ($instansi_pilihan == "Lainnya") {
        $instansi = mysqli_real_escape_string($koneksi, $_POST['instansi_lainnya']);
    } else {
        $instansi = $instansi_pilihan;
    }
    
    $keperluan  = mysqli_real_escape_string($koneksi, $_POST['keperluan']);

    $sql = "UPDATE tamu SET nama='$nama', email='$email', no_telepon='$no_telepon', instansi='$instansi', keperluan='$keperluan' WHERE id='$id'";

    if (mysqli_query($koneksi, $sql)) {
        mysqli_close($koneksi);
        header("Location: tabel_tamu.php");
        exit();
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}

// Daftar array instansi resmi untuk dicocokkan di dropdown select
$daftar_instansi = [
    "Kementerian Dalam Negeri",
    "Kementerian Luar Negeri",
    "Kementerian Pertahanan",
    "Kementerian Keuangan",
    "Kementerian Kesehatan",
    "Kementerian Sosial",
    "Kementerian Komunikasi dan Informatika",
    "Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi",
    "Kementerian Agama",
    "Kementerian Badan Usaha Milik Negara",
    "Pemerintah Provinsi (Pemprov)",
    "Pemerintah Kabupaten/Kota (Pemkab/Pemkot)",
    "Tentara Nasional Indonesia (TNI)",
    "Kepolisian Republik Indonesia (POLRI)",
    "Universitas / Sekolah / Institusi Pendidikan",
    "Masyarakat Umum / Perorangan"
];

// Cek apakah data instansi lama terdaftar di sistem dropdown atau hasil ketik manual
$is_custom_instansi = !in_array($data['instansi'], $daftar_instansi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Tamu</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://jsdelivr.net" rel="stylesheet">
    <!-- Select2 CSS untuk Fitur Pencarian di Dropdown -->
    <link href="https://jsdelivr.net" rel="stylesheet" />
    <style>
        body { background-color: #f5f6fa; font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 40px; }
        .form-card { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        h3 { margin-top: 0; margin-bottom: 25px; color: #333; font-weight: 700; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #495057; font-size: 14px; }
        .form-control-custom { width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; box-sizing: border-box; font-size: 15px; }
        
        .select2-container--default .select2-selection--single {
            height: 44px !important;
            padding: 7px 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 42px !important; }
        .row-flex { display: flex; gap: 20px; }
        .col-flex { flex: 1; }
        .btn-container { display: flex; justify-content: space-between; margin-top: 30px; }
        .btn { padding: 11px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-save { background-color: #624bff; color: white; }
        .btn-save:hover { background-color: #4e36cc; }
        .btn-cancel { background-color: #6c757d; color: white; padding: 10px 20px; border: 1px solid #ced4da; background: #ffffff; color: #6c757d; }
        .btn-cancel:hover { background: #f8f9fa; }
        @media (max-width: 576px) { .row-flex { flex-direction: column; gap: 0; } }
    </style>
</head>
<body>

    <div class="form-card">
        <h3>Edit Data Kunjungan</h3>
        <form method="POST">
            <div class="form-group">
                <label for="nama">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" class="form-control-custom" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
            </div>

            <div class="row-flex">
                <div class="col-flex form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" class="form-control-custom" id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>">
                </div>
                <div class="col-flex form-group">
                    <label for="no_telepon">No. Telepon / WhatsApp</label>
                    <input type="text" class="form-control-custom" id="no_telepon" name="no_telepon" value="<?php echo htmlspecialchars($data['no_telepon']); ?>">
                </div>
            </div>

            <!-- DROPDOWN PADA HALAMAN EDIT -->
            <div class="form-group">
                <label for="instansi">Instansi / Perusahaan <span style="color: red;">*</span></label>
                <select class="form-control-custom" id="instansi" name="instansi" required style="width: 100%;">
                    <option value="" disabled>-- Cari atau Pilih Instansi Anda --</option>
                    <?php 
                    foreach ($daftar_instansi as $inst) {
                        $selected = ($data['instansi'] == $inst) ? 'selected' : '';
                        echo "<option value='$inst' $selected>$inst</option>";
                    }
                    ?>
                    <option value="Lainnya" <?php echo ($is_custom_instansi) ? 'selected' : ''; ?>>-- Swasta / Cari Tidak Ada (Ketik Manual) --</option>
                </select>
                
                <!-- Input Manual Tambahan -->
                <input type="text" class="form-control-custom" id="instansi_lainnya" name="instansi_lainnya" 
                       value="<?php echo ($is_custom_instansi) ? htmlspecialchars($data['instansi']) : ''; ?>" 
                       placeholder="Ketik spesifik nama PT / Dinas / Instansi Anda di sini..." 
                       style="display: <?php echo ($is_custom_instansi) ? 'block' : 'none'; ?>; margin-top: 10px;">
            </div>

            <div class="form-group" style="margin-top: 25px;">
                <label for="keperluan">Keperluan Kunjungan <span style="color: red;">*</span></label>
                <textarea class="form-control-custom" id="keperluan" name="keperluan" rows="4" required style="resize: vertical;"><?php echo htmlspecialchars($data['keperluan']); ?></textarea>
            </div>

            <div class="btn-container">
                <a href="tampilkan.php" class="btn btn-cancel">Batal</a>
                <button type="submit" name="update" class="btn btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <!-- Pustaka JQuery dan Select2 JS -->
    <script src="https://jquery.com"></script>
    <script src="https://jsdelivr.net"></script>
    <script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('#instansi').select2({
            placeholder: "-- Cari atau Pilih Instansi Anda --",
            allowClear: true
        });
        
        // Memperbaiki deteksi perubahan agar fungsi select2 sinkron dengan input teks manual
        $('#instansi').on('select2:select', function (e) {
            cekInstansiLainnya(e.params.data.id);
        });

        // Jalankan pengecekan di awal saat halaman pertama kali dimuat
        cekInstansiLainnya($('#instansi').val());
    });

    function cekInstansiLainnya(nilai){
        var elemenKetikManual = $('#instansi_lainnya');
        if(nilai == 'Lainnya') {
            elemenKetikManual.show();
            elemenKetikManual.prop('required', true);
        } else {
            elemenKetikManual.hide();
            elemenKetikManual.prop('required', false);
        }
    }
    </script>
</body>
</html>
