<?php
session_start();

// 1. KEAMANAN: Pastikan user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// 2. KONEKSI DATABASE
$host     = "localhost";
$username = "root";
$password = "Slebew234";
$database = "db_buku_tamu";

$koneksi = @mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal! Periksa HeidiSQL Anda.");
}

// 3. AMBIL DATA TAMU BERDASARKAN ID
// Contoh url: cetak_tiket.php?id=1
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Tamu tidak ditemukan! Silakan pilih tamu dari Tabel Tamu.");
}

$id_tamu = mysqli_real_escape_string($koneksi, $_GET['id']);
$query   = "SELECT * FROM tamu WHERE id = '$id_tamu'"; 
$result  = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) === 0) {
    die("Data tamu dengan ID tersebut tidak ada di database.");
}

$tamu = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Buku Tamu - <?php echo htmlspecialchars($tamu['nama'] ?? 'Tamu'); ?></title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace; /* Font struk standar */
        }
        body {
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 10px;
        }
        /* Desain Struk/Tiket */
        .ticket-box {
            background-color: #ffffff;
            width: 320px; /* Ukuran standar printer thermal 80mm */
            padding: 20px;
            border: 1px dashed #333;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header h2 {
            font-size: 1.2rem;
            font-weight: bold;
            color: #1e88e5;
            margin-bottom: 4px;
        }
        .header p {
            font-size: 0.75rem;
            color: #666;
        }
        .barcode-area {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ddd;
            font-weight: bold;
            letter-spacing: 4px;
            font-size: 1.1rem;
            background-color: #fafafa;
        }
        .info-table {
            width: 100%;
            font-size: 0.85rem;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table tr td {
            padding: 6px 0;
            vertical-align: top;
        }
        .info-table tr td:first-child {
            width: 35%;
            color: #555;
        }
        .info-table tr td:nth-child(2) {
            width: 5%;
        }
        .footer {
            text-align: center;
            border-top: 1px dashed #333;
            padding-top: 12px;
            margin-top: 15px;
            font-size: 0.75rem;
            color: #555;
        }
        /* Tombol Aksi (Disembunyikan saat dicetak) */
        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-print {
            background-color: #4caf50;
            color: white;
        }
        .btn-back {
            background-color: #757575;
            color: white;
        }
        
        /* CSS Khusus Cetak Printer */
        @media print {
            body {
                background-color: transparent;
                padding: 0;
            }
            .ticket-box {
                box-shadow: none;
                border: none;
                width: 100%;
            }
            .actions {
                display: none; /* Tombol tidak ikut tercetak */
            }
        }
    </style>
</head>
<body>

    <!-- KARTU TIKET -->
    <div class="ticket-box">
        <div class="header">
            <h2>RSI KENDAL</h2>
            <p>Sistem Informasi Buku Tamu Digital</p>
            <p style="font-size: 0.7rem; margin-top: 5px;">
                Waktu: <?php echo date('d-m-Y H:i', strtotime($tamu['tanggal'] ?? 'now')); ?>
            </p>
        </div>

        <!-- Nomor Antrean / Kode Tiket Otomatis -->
        <div class="barcode-area">
            T-<?php echo str_pad($tamu['id'], 4, '0', STR_PAD_LEFT); ?>
        </div>

        <!-- Detail Data Tamu -->
        <table class="info-table">
            <tr>
                <td>Nama Tamu</td>
                <td>:</td>
                <td><strong><?php echo htmlspecialchars($tamu['nama'] ?? '-'); ?></strong></td>
            </tr>
            <tr>
                <td>Instansi</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($tamu['instansi'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Keperluan</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($tamu['keperluan'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Tujuan</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($tamu['tujuan'] ?? '-'); ?></td>
            </tr>
        </table>

        <div class="footer">
            <p>Silakan simpan tiket ini selama berada di lingkungan RSI Kendal.</p>
            <p style="margin-top: 8px; font-weight: bold;">Terima Kasih Atas Kunjungan Anda</p>
        </div>
    </div>

    <!-- TOMBOL NAVIGASI -->
    <div class="actions">
        <button onclick="window.print();" class="btn btn-print">🖨️ CETAK TIKET</button>
        <a href="tabel_tamu.php" class="btn btn-back">KEMBALI</a>
    </div>

</body>
</html>
