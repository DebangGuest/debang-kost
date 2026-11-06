# Debang Kost - Web Management System

Aplikasi web manajemen pendaftaran dan data tamu/kost Debang Guest House.

## Fitur
- Form input pendaftaran data penghuni/tamu
- Koneksi database otomatis via PHP & MySQL
- Penyimpanan dan penarikan data secara terintegrasi

## Cara Menjalankan Secara Lokal
1. Pindahkan folder proyek ke `htdocs` (jika menggunakan XAMPP).
2. Nyalakan modul **Apache** dan **MySQL** di XAMPP Control Panel.
3. Buka browser dan akses `http://localhost/phpmyadmin/`.
4. Buat database baru (misal: `debang_guest`) lalu import file `db_debang_guest.sql`.
5. Sesuaikan konfigurasi koneksi di file `koneksi.php` jika diperlukan.
6. Buka aplikasi di browser melalui `http://localhost/debang-kost/index.php`.
