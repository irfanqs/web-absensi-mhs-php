# Sistem Absensi Mahasiswa (PHP Native)

Sistem Absensi Mahasiswa ini adalah aplikasi berbasis web yang dibangun menggunakan **PHP Native** dengan arsitektur **MVC (Model-View-Controller)**. Sistem ini memiliki fitur manajemen hak akses (RBAC - Role Based Access Control) untuk mendukung berbagai peran pengguna, seperti Admin, Dosen, dan Mahasiswa.

## Fitur Utama

- **Role-Based Access Control (RBAC):** Manajemen fleksibel untuk *User*, *Role*, dan *Permission*.
- **Mahasiswa:**
  - Melakukan presensi (Check-in / Pulang).
  - Melihat riwayat presensi sendiri.
- **Dosen / Staff:**
  - Memantau rekap absensi harian mahasiswa.
  - Melihat seluruh data absensi.
- **Administrator:**
  - Mengelola data pengguna, role, dan spesifikasi izin akses sistem.
  - Manajemen absensi keseluruhan.

## Teknologi

- **Backend:** PHP Native (tanpa framework)
- **Frontend:** HTML, CSS (Custom styling mirip Bootstrap/Tailwind), JavaScript
- **Database:** MySQL / MariaDB

## Prasyarat

- PHP >= 7.4 (rekomendasi PHP 8)
- Server lokal (seperti XAMPP, MAMP, Laragon, dll.)
- MySQL / MariaDB

## Instalasi

1. **Clone repositori ini:**
   ```bash
   git clone https://github.com/irfanqs/web-absensi-mhs-php.git
   cd web-absensi-mhs-php
   ```

2. **Setup Database:**
   - Buka phpMyAdmin atau alat klien MySQL lainnya.
   - Buat sebuah database baru (misalnya `absensi_db`).
   - Import file `database/schema.sql` ke dalam database yang baru dibuat tersebut.

3. **Konfigurasi Database di Aplikasi:**
   - Buka file `config/database.php`.
   - Ubah konfigurasi database agar sesuai dengan server lokal Anda:
     ```php
     <?php
     return [
         'host' => '127.0.0.1',
         'dbname' => 'absensi_db', // ganti sesuai nama database Anda
         'username' => 'root',     // username db
         'password' => ''          // password db
     ];
     ```

4. **Jalankan Aplikasi:**
   - Jika Anda menggunakan XAMPP/MAMP, letakkan folder project ini di dalam `htdocs` atau `www`, lalu akses melalui browser: `http://localhost/nama-folder-project`
   - Atau Anda bisa menggunakan PHP Built-in Server melalui terminal di dalam folder direktori project ini:
     ```bash
     php -S localhost:8000
     ```
     Lalu akses di browser: `http://localhost:8000/`

## Kredensial Akun (Default)

Setelah melakukan import database SQL, Anda dapat login menggunakan kredensial default:

- **Admin**
  - Username: `admin`
  - Password: `aku233`
- **Mahasiswa**
  - Username: (Bisa ditambahkan melalui panel admin)
- **Dosen**
  - Username: (Bisa ditambahkan melalui panel admin)

## Struktur Folder

```text
├── config/             # Pengaturan database
├── controllers/        # Logika aplikasi (Controller MVC)
├── core/               # File sistem utama (Routing, Database, Auth)
├── database/           # File SQL untuk skema database
├── models/             # Model data (Model MVC)
├── views/              # Tampilan Frontend (View MVC)
├── index.php           # Entry-point aplikasi (Front Controller)
└── README.md           # Dokumentasi ini
```

---
*Proyek ini merupakan pengembangan dari sistem base RBAC yang dimodifikasi untuk keperluan pencatatan kehadiran.*