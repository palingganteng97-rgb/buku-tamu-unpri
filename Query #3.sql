-- Membuat tabel data_instansi baru di database
CREATE TABLE IF NOT EXISTS `data_instansi` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `kategori` VARCHAR(100) NOT NULL,
  `nama_instansi` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Memasukkan daftar isi instansi awal agar tabel tidak kosong
INSERT INTO `data_instansi` (`kategori`, `nama_instansi`) VALUES
('Kementerian Negara', 'Kementerian Dalam Negeri (Kemendagri)'),
('Kementerian Negara', 'Kementerian Luar Negeri (Kemenlu)'),
('Kementerian Negara', 'Kementerian Pertahanan (Kemenhan)'),
('Kementerian Negara', 'Kementerian Keuangan (Kemenkeu)'),
('Kementerian Negara', 'Kementerian Kesehatan (Kemenkes)'),
('Kementerian Negara', 'Kementerian Sosial (Kemensos)'),
('Kementerian Negara', 'Kementerian Komunikasi dan Informatika (Kemenkominfo)'),
('Kementerian Negara', 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi (Kemendikbudristek)'),
('Kementerian Negara', 'Kementerian Agama (Kemenag)'),
('Kementerian Negara', 'Kementerian Badan Usaha Milik Negara (KemenBUMN)'),
('Lembaga Tinggi Negara', 'Dewan Perwakilan Rakyat (DPR-RI)'),
('Lembaga Tinggi Negara', 'Majelis Permusyawaratan Rakyat (MPR-RI)'),
('Lembaga Tinggi Negara', 'Mahkamah Agung (MA)'),
('Lembaga Tinggi Negara', 'Mahkamah Konstitusi (MK)'),
('Pemerintah Daerah', 'Pemerintah Provinsi (Pemprov) Seluruh Indonesia'),
('Pemerintah Daerah', 'Pemerintah Kabupaten / Kota (Pemkab / Pemkot) Seluruh Indonesia'),
('Keamanan & Pertahanan', 'Tentara Nasional Indonesia (TNI)'),
('Keamanan & Pertahanan', 'Kepolisian Republik Indonesia (POLRI)'),
('Korporasi Negara', 'Badan Usaha Milik Negara (BUMN) / BUMD'),
('Pendidikan & Umum', 'Universitas / Sekolah / Institusi Pendidikan'),
('Umum', 'Masyarakat Umum / Perorangan / Swasta Lainnya');
