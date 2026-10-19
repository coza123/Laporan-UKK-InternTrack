# InternTrack - Sistem Monitoring Kegiatan PKL Siswa

Aplikasi web untuk monitoring Praktik Kerja Lapangan (PKL) siswa SMK.

## Spesifikasi
- **Platform**: Web
- **Bahasa**: PHP 8.x
- **Database**: MySQL / MariaDB
- **Frontend**: HTML, CSS, Bootstrap 5, JavaScript

## Fitur
### Admin / Pembimbing
- Login & Dashboard (statistik real-time dari database)
- CRUD Data Siswa (dengan search & filter kelas)
- CRUD Data Perusahaan (search)
- Data Pembimbing
- Manajemen Penempatan PKL (Siswa → Perusahaan → Pembimbing + Status)
- Lihat Jurnal Kegiatan siswa
- Rekap Absensi + Perhitungan Status Kehadiran otomatis
- Input Monitoring (nilai, catatan, rekomendasi)
- Laporan (Siswa PKL, Kehadiran, Monitoring) + print

### Siswa
- Login & Dashboard personal
- Lihat Profil & Tempat PKL
- Input Jurnal Kegiatan
- Input Absensi (Hadir/Izin/Sakit/Alpa)
- Lihat Hasil Monitoring
- Status kehadiran otomatis (Sangat Baik / Baik / Perlu Perhatian / Perlu Pembinaan)

## Struktur Folder
```
InternTrack/
├── config/          # Konfigurasi database
├── assets/          # CSS, JS
├── admin/           # Modul admin/pembimbing
├── siswa/           # Modul siswa
├── auth/            # Login & Logout
├── laporan/         # Modul laporan
├── includes/        # Header, footer, auth helper
├── index.php
└── README.md
```

## Instalasi

1. Pastikan PHP >= 8.0 dan MySQL/MariaDB terinstall.
2. Import database:
   ```bash
   mysql -u root -p < database/interntrack.sql
   ```
3. Sesuaikan kredensial di `config/database.php` jika perlu.
4. Jalankan dengan PHP built-in server (dari folder parent):
   ```bash
   php -S localhost:8000
   ```
   Lalu buka: http://localhost:8000/InternTrack/

   Atau letakkan di htdocs/www Apache/Nginx.

## Hosting

1. Upload isi folder `InternTrack` ke document root hosting (umumnya `public_html`) atau ke subfolder di dalamnya.
2. Buat database MySQL dan user database melalui panel hosting, lalu berikan hak akses user tersebut ke database.
3. Import `database/interntrack.sql` ke database yang baru dibuat.
4. Atur variabel lingkungan `DB_HOST`, `DB_USER`, `DB_PASS`, dan `DB_NAME` pada hosting. Jika hosting tidak menyediakan pengaturan variabel lingkungan PHP, ubah nilai default pada `config/database.php` sesuai informasi database dari penyedia hosting. Jangan gunakan kredensial XAMPP (`root` tanpa kata sandi) di hosting.
5. Pastikan hosting menggunakan PHP 8 atau lebih baru dengan ekstensi `mysqli` aktif.

Tautan internal aplikasi mengikuti lokasi pemasangan, sehingga aplikasi dapat diakses dari document root maupun subfolder tanpa mengubah prefiks URL di kode.

## Akun Demo
| Role        | Username      | Password       |
|-------------|---------------|----------------|
| Admin       | admin         | admin123       |
| Pembimbing  | pembimbing1   | pembimbing123  |
| Siswa       | siswa01       | siswa123       |

## Aturan Status Kehadiran
- >= 90% → Sangat Baik
- 75% – 89% → Baik
- 60% – 74% → Perlu Perhatian
- < 60% → Perlu Pembinaan

## Data Dummy
- 25 Siswa
- 10 Perusahaan
- 10 Pembimbing
- 10 Penempatan
- 30 Jurnal
- 30 Absensi
- 10 Monitoring

Dibuat untuk UKK SMK Program Keahlian Rekayasa Perangkat Lunak - Skema Junior Coder.
SMK Hidayah Semarang | 2026
