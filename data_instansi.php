<?php
session_start();

// 1. Proteksi Halaman Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// 2. Koneksi ke Database
$koneksi = mysqli_connect("localhost", "root", "Slebew234", "db_buku_tamu");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 3. PROSES TAMBAH DATA (Langsung di halaman yang sama)
if (isset($_POST['tambah_instansi'])) {
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $nama_instansi = mysqli_real_escape_string($koneksi, $_POST['nama_instansi']);
    
    $query_tambah = "INSERT INTO data_instansi (kategori, nama_instansi) VALUES ('$kategori', '$nama_instansi')";
    if (mysqli_query($koneksi, $query_tambah)) {
        header("Location: data_instansi.php");
        exit();
    }
}

// 4. PROSES EDIT DATA (Langsung di halaman yang sama)
if (isset($_POST['update_instansi'])) {
    $id = intval($_POST['id']);
    $kategori_pilihan = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    
    // Logika menangkap opsi kategori 'Lainnya' jika dipilih
    if ($kategori_pilihan == "Lainnya") {
        $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori_lainnya']);
    } else {
        $kategori = $kategori_pilihan;
    }
    
    $nama_instansi = mysqli_real_escape_string($koneksi, $_POST['nama_instansi']);
    
    $query_update = "UPDATE data_instansi SET kategori='$kategori', nama_instansi='$nama_instansi' WHERE id=$id";
    if (mysqli_query($koneksi, $query_update)) {
        header("Location: data_instansi.php");
        exit();
    }
}

// 5. Sistem Pencarian Data
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($koneksi, $_GET['search']);
    $query = mysqli_query($koneksi, "SELECT * FROM data_instansi WHERE nama_instansi LIKE '%$search%' OR kategori LIKE '%$search%' ORDER BY nama_instansi ASC");
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM data_instansi ORDER BY nama_instansi ASC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Instansi - Sistem Informasi Buku Tamu</title>
    <style>
        /* CSS Utama Layout Sistem */
        * { box-sizing: border-box; }
        body { background-color: #f4f6f9; font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .top-navbar { background-color: #2c5e8a; color: white; padding: 15px 20px; font-size: 16px; font-weight: bold; }
        .dashboard-layout { display: flex; min-height: calc(100vh - 54px); }
        
        /* Sidebar Menu */
        .sidebar-menu { width: 220px; background-color: #3e3e3e; flex-shrink: 0; padding-top: 10px; display: flex; flex-direction: column; justify-content: space-between; }
        .menu-top-group { width: 100%; }
        .sidebar-menu a { display: block; color: #dfdfdf; padding: 15px 20px; text-decoration: none; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: #2c5e8a; color: white; }
        .sidebar-menu .logout-link { border-top: 1px solid #4f4f4f; border-bottom: none; color: #ff8888; text-align: left; }
        .sidebar-menu .logout-link:hover { background-color: #d9534f; color: white; }
        
        /* Konten Utama Sebelah Kanan */
        .main-container { flex-grow: 1; padding: 25px; overflow-x: hidden; background-color: #f4f6f9; }
        .table-card { background: white; border-radius: 4px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); padding: 20px; border: 1px solid #dee2e6; }
            .table-header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; width: 100%; }
    .search-container { display: flex; align-items: center; gap: 12px; }
    .search-input { padding: 9px 14px; border: 1px solid #DCDCE6; border-radius: 6px; font-size: 14px; width: 200px; outline: none; background-color: #ffffff; color: #333333; }
    .btn-cari { padding: 9px 16px; background-color: #3498DB; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 14px; }
    .btn-cari:hover { background-color: #2980B9; }
    .btn-create { padding: 9px 18px; background-color: #2ECC71; color: white; text-decoration: none; border-radius: 6px; font-weight: 500; font-size: 14px; display: inline-flex; align-items: center; border: none; cursor: pointer; }
    .btn-create:hover { background-color: #27AE60; }
    .badge-klasifikasi { display: inline-block; padding: 6px 14px; font-size: 11px; font-weight: 600; color: white; background-color: #2C5E8A; border-radius: 20px; letter-spacing: 0.5px; text-transform: uppercase; text-align: center; }
    .action-buttons { display: flex; gap: 8px; justify-content: center; align-items: center; }
    .btn-action { width: 34px; height: 34px; border-radius: 50%; border: none; display: inline-flex; align-items: center; justify-content: center; color: white; cursor: pointer; text-decoration: none; font-size: 13px; }
    .btn-edit { background-color: #1ABC9C; }
    .btn-edit:hover { background-color: #16A085; }
    .btn-delete { background-color: #E74C3C; }
    .btn-delete:hover { background-color: #C0392B; }

        /* Desain Struktur Garis Tabel Kokoh */
        .table-responsive { width: 100%; overflow-x: auto; margin-top: 15px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; font-size: 13px; background-color: #fff; }
        .table, .table th, .table td { border: 1px solid #cccccc !important; }
        .table th, .table td { padding: 10px 12px; vertical-align: middle; }
        .table-light { background-color: #f8f9fa; color: #212529; font-weight: bold; }
        .d-flex { display: flex !important; }
        .gap-2 { gap: 0.5rem !important; }
        
        /* Input & Tombol */
        .form-control, .form-select { padding: 6px 12px; font-size: 13px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box; }
        .btn { display: inline-block; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid transparent; padding: 8px 16px; font-size: 12px; border-radius: 4px; text-decoration: none; cursor: pointer; }
        .btn-primary { color: #fff; background-color: #425fff; }
        .btn-secondary { color: #fff; background-color: #6c757d; }
        .btn-light { color: #000; background-color: #f8f9fa; border: 1px solid #ced4da; }

        /* Tempelkan baris ini di dalam tag <style> file data_instansi.php */
.header-nav { 
    background-color: #3b5998; 
    color: white; 
    padding: 15px 20px; 
    font-size: 16px; 
    font-weight: 600; 
    text-align: center; 
}

        /* Tombol Ikon Kotak Aksi */
        .btn-action-edit { background-color: #0ea5e9; color: white; border: none; padding: 6px 10px; border-radius: 6px; font-size: 14px; cursor: pointer; display: inline-block; }
        .btn-action-edit:hover { background-color: #0284c7; }
        .btn-action-hapus { background-color: #ef4444; color: white; border: none; padding: 6px 10px; border-radius: 6px; font-size: 14px; text-decoration: none; display: inline-block; cursor: pointer; }
        .btn-action-hapus:hover { background-color: #dc2626; }

        /* MODAL POP-UP MANUAl OFFLINE INDEPENDEN */
        .custom-modal { display: none !important; position: fixed !important; z-index: 9999 !important; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5) !important; align-items: center; justify-content: center; }
        .custom-modal-content { background-color: #fff; padding: 30px; border-radius: 8px; width: 100%; max-width: 500px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); box-sizing: border-box; }
        .custom-modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .custom-modal-title { margin: 0; font-size: 16px; font-weight: bold; color: #333; }
        .custom-close { font-size: 24px; font-weight: bold; color: #aaa; cursor: pointer; border: none; background: none; }
        .custom-close:hover { color: #000; }
        .custom-modal-footer { display: flex; justify-content: space-between; margin-top: 25px; border-top: 1px solid #eee; padding-top: 15px; }
        .btn-kirim-modal { background-color: #6342ff; color: white; font-weight: bold; border: none; }
        .btn-kirim-modal:hover { background-color: #4f30e6; }
        .modal-body label { display: block; font-weight: bold; font-size: 13px; color: #333; margin-bottom: 6px; text-align: left; }
        .modal-body .form-select, .modal-body .form-control { width: 100%; padding: 10px; font-size: 13px; margin-bottom: 15px; }
    </style>
</head>
<body>

   <!-- Ganti baris teks judul di bawah tag body menjadi seperti ini -->
<div class="header-nav">
    Buku Tamu RSI Kendal
</div>


    <!-- Layout Utama -->
    <div class="dashboard-layout">
        
       <!-- Sidebar Menu yang Sudah Dirapikan -->
<!-- Sidebar Menu di file data_instansi.php -->
<div class="sidebar-menu" style="display: flex; flex-direction: column; min-height: calc(100vh - 52px); justify-content: space-between;">
    <div>
        <a href="tampilkan.php">DASHBOARD</a>
        <a href="tabel_tamu.php">TABEL TAMU</a>
        <a href="data_instansi.php" class="active">DATA INSTANSI</a> <!-- PINDAHKAN KE SINI KAWAN -->
        <a href="manajemen_akun.php">MANAJEMEN AKUN</a> <!-- HAPUS kata class="active" dari sini -->
    </div>
    <div style="padding-bottom: 20px;">
        <a href="logout.php" class="logout-btn" style="color: #e74c3c; font-weight: 600; text-decoration: none; display: block; padding: 15px 20px;">LOGOUT</a>
    </div>
</div>



        <!-- Area Konten Utama Sebelah Kanan -->
        <div class="main-container">
            <div class="table-card">
                
                           <div class="table-header-flex">
                <div class="left-title">
                    <h2 style="font-size: 22px; font-weight: 600; color: #2C3E50; margin: 0;">DATA INSTANSI</h2>
                </div>
                <div class="search-container">
                    <form method="GET" action="data_instansi.php" style="display: flex; gap: 8px; margin: 0;">
                        <input type="text" name="cari" class="search-input" placeholder="Cari Instansi...">
                        <button type="submit" class="btn-cari">Cari</button>
                    </form>
                    <a href="tambah_instansi.php" class="btn-create">+ Tambah Instansi Baru</a>
                </div>
            </div>


                <!-- Struktur Render Tabel -->
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%; text-align: center;">No</th>
                                <th style="width: 25%;">Klasifikasi Lembaga</th>
                                <th style="width: 55%;">Nama Instansi / Perusahaan Resmi (Urut A-Z)</th>
                                <th style="width: 15%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($query) > 0) :
                                while ($row = mysqli_fetch_assoc($query)) : 
                                    ?>
                            <tr>
                                <td style="text-align: center; font-weight: bold;"><?= $no++; ?></td>
                                <td><span class="badge-klasifikasi"><?= htmlspecialchars(strtoupper($row['kategori'])); ?></span></td>
                                <td style="font-weight: 600; color: #333;"><?= htmlspecialchars($row['nama_instansi']); ?></td>
                                <td style="text-align: center; vertical-align: middle;">
                    <div class="action-buttons">
                        <!-- Tombol Edit Bulat Toska dengan Fungsi Modal Anda -->
                        <button type="button" class="btn-action btn-edit" title="Edit" onclick="bukaModalEdit(<?= $row['id']; ?>, '<?= htmlspecialchars($row['nama_instansi'], ENT_QUOTES); ?>')">📝</button>
                        
                        <!-- Tombol Hapus Bulat Merah -->
                        <a href="hapus_instansi.php?id=<?= $row['id']; ?>" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data instansi ini?')">🗑️</a>
                    </div>
                </td>

                            </tr>
                            <?php 
                                endwhile; 
                            else :
                                echo "<tr><td colspan='4' style='text-align: center; color: #888; padding: 20px;'>Data instansi tidak ditemukan.</td></tr>";
                            endif; 
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- ==================== 1. POP-UP MODAL BOX: TAMBAH DATA ==================== -->
    <div class="custom-modal" id="modalTambah">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">Tambah Data Instansi Baru</h5>
                <button type="button" class="custom-close" onclick="tutupModalTambah()">&times;</button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Klasifikasi Lembaga / Kategori *</label>
                        <select name="kategori" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <option value="Kementerian Negara">Kementerian Negara</option>
                            <option value="Lembaga Tinggi Negara">Lembaga Tinggi Negara</option>
                            <option value="Pemerintah Daerah">Pemerintah Daerah</option>
                            <option value="Keamanan & Pertahanan">Keamanan & Pertahanan</option>
                            <option value="Korporasi Negara (BUMN/BUMD)">Korporasi Negara (BUMN/BUMD)</option>
                            <option value="Pendidikan & Umum">Pendidikan & Umum</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Nama Instansi / Perusahaan Resmi *</label>
                        <input type="text" name="nama_instansi" class="form-control" required placeholder="Masukkan nama instansi resmi...">
                    </div>
                </div>
                <div class="custom-modal-footer">
                    <button type="button" class="btn btn-light border px-4" onclick="tutupModalTambah()">Kembali</button>
                    <button type="submit" name="tambah_instansi" class="btn btn-kirim-modal px-4">Kirim Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== 2. POP-UP MODAL BOX: EDIT DATA ==================== -->
    <div class="custom-modal" id="modalEdit">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">Edit Data Instansi</h5>
                <button type="button" class="custom-close" onclick="tutupModalEdit()">&times;</button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <!-- Penampung ID Data -->
                    <input type="hidden" name="id" id="edit-id">
                    
                    <div class="mb-3">
                        <label>Klasifikasi Lembaga / Kategori *</label>
                        <select name="kategori" id="edit-kategori" class="form-select" onchange="toggleKategoriLainnya()" required>
                            <option value="Kementerian Negara">Kementerian Negara</option>
                            <option value="Lembaga Tinggi Negara">Lembaga Tinggi Negara</option>
                            <option value="Pemerintah Daerah">Pemerintah Daerah</option>
                            <option value="Keamanan & Pertahanan">Keamanan & Pertahanan</option>
                            <option value="Korporasi Negara (BUMN/BUMD)">Korporasi Negara (BUMN/BUMD)</option>
                            <option value="Pendidikan & Umum">Pendidikan & Umum</option>
                            <option value="Lainnya">Lainnya (Ketik Manual)</option>
                        </select>
                    </div>
                    
                    <!-- Kotak Input Text Tambahan jika memilih opsi 'Lainnya' -->
                    <div class="mb-3" id="input_kategori_lainnya" style="display: none;">
                        <label>Tuliskan Kategori Kustom Anda *</label>
                        <input type="text" name="kategori_lainnya" id="field_kategori_lainnya" class="form-control" placeholder="Contoh: Organisasi Swasta">
                    </div>
                    
                    <div class="mb-2">
                        <label>Nama Instansi / Perusahaan Resmi *</label>
                        <input type="text" name="nama_instansi" id="edit-nama" class="form-control" required>
                    </div>
                </div>
                <div class="custom-modal-footer">
                    <button type="button" class="btn btn-light border px-4" onclick="tutupModalEdit()">Batal</button>
                    <button type="submit" name="update_instansi" class="btn btn-kirim-modal px-4">Update Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT ENGINE INTERAKTIF KHUSUS OFFLINE -->
    <script>
        // 1. Fungsi Pop-Up Tambah Data
        function bukaModalTambah() {
            document.getElementById("modalTambah").style.setProperty("display", "flex", "important");
        }
        function tutupModalTambah() {
            document.getElementById("modalTambah").style.setProperty("display", "none", "important");
        }

        // 2. Fungsi Pop-Up Edit Data (Suntik data dari baris tabel ke kotak form)
        function bukaModalEdit(id, kategori, nama) {
            document.getElementById("edit-id").value = id;
            document.getElementById("edit-nama").value = nama;
            
            var selectBox = document.getElementById("edit-kategori");
            var inputDiv = document.getElementById("input_kategori_lainnya");
            var inputField = document.getElementById("field_kategori_lainnya");
            
            // Cek apakah kategori ada di dalam daftar default selectbox
            var opsiValid = false;
            for (var i = 0; i < selectBox.options.length; i++) {
                if (selectBox.options[i].value === kategori) {
                    opsiValid = true;
                    break;
                }
            }
            
            if (opsiValid) {
                selectBox.value = kategori;
                inputDiv.style.display = "none";
                inputField.removeAttribute("required");
                inputField.value = "";
            } else {
                selectBox.value = "Lainnya";
                inputDiv.style.display = "block";
                inputField.setAttribute("required", "required");
                inputField.value = kategori;
            }
            
            document.getElementById("modalEdit").style.setProperty("display", "flex", "important");
        }
        
        function tutupModalEdit() {
            document.getElementById("modalEdit").style.setProperty("display", "none", "important");
        }

        // 3. Fungsi Kendali Dropdown Opsi 'Lainnya' di dalam modal edit
        function toggleKategoriLainnya() {
            var selectBox = document.getElementById("edit-kategori");
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

        // 4. Tutup otomatis pop-up jika area hitam luar diklik
        window.onclick = function(event) {
            var mTambah = document.getElementById("modalTambah");
            var mEdit = document.getElementById("modalEdit");
            if (event.target == mTambah) { mTambah.style.setProperty("display", "none", "important"); }
            if (event.target == mEdit) { mEdit.style.setProperty("display", "none", "important"); }
        }
    </script>

</body>
</html>

