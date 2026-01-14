# PANDUAN SETUP - Website PKPT

## Program Kerja Pengawasan Tahunan - Kemenko PMK RI

---

## 📋 Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MySQL 5.7+ atau MariaDB 10.3+
- Composer
- Web Server (Apache/Nginx) atau PHP Built-in Server

---

## 🚀 Langkah Instalasi

### 1. Konfigurasi Database

Buat database baru untuk aplikasi:

```sql
CREATE DATABASE pkpt_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Konfigurasi Environment

Salin file `env` menjadi `.env`:

```bash
copy env .env
```

Edit file `.env` dan sesuaikan konfigurasi database:

```ini
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
CI_ENVIRONMENT = development

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = localhost
database.default.database = pkpt_db
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

> **Catatan**: Sesuaikan `hostname`, `database`, `username`, dan `password` dengan konfigurasi database Anda.

### 3. Jalankan Migration

Jalankan migration untuk membuat tabel database:

```bash
php spark migrate
```

Output yang diharapkan:

```
Running all new migrations...
Migrating: 2026-01-08-020000_BuatTabelProgramKerja
Migrated: 2026-01-08-020000_BuatTabelProgramKerja
```

### 4. Jalankan Server Development

```bash
php spark serve
```

Aplikasi akan berjalan di: `http://localhost:8080`

---

## 📁 Struktur File yang Dibuat

### Backend

```
app/
├── Controllers/
│   └── ProgramKerja.php          # Controller utama
├── Models/
│   └── ProgramKerjaModel.php     # Model dengan validasi
├── Database/
│   └── Migrations/
│       └── 2026-01-08-020000_BuatTabelProgramKerja.php
└── Views/
    ├── layouts/
    │   └── utama.php             # Layout template
    └── program_kerja/
        ├── daftar.php            # Halaman list
        └── form.php              # Form tambah/edit
```

### Frontend

```
public/
└── assets/
    ├── css/
    │   └── program-kerja.css     # Styling
    └── js/
        └── program-kerja.js      # JavaScript
```

### Storage

```
writable/
└── uploads/
    └── dokumen_output/           # Folder upload dokumen
```

---

## 🌐 Akses Aplikasi

Setelah server berjalan, akses halaman berikut:

- **Halaman Utama Program Kerja**: `http://localhost:8080/program-kerja`
- **Tambah Program Kerja**: `http://localhost:8080/program-kerja/tambah`

---

## 📝 Fitur yang Tersedia

### ✅ Sudah Diimplementasikan

1. **Daftar Program Kerja**

   - Tabel dengan 10 kolom sesuai struktur
   - Pencarian berdasarkan nama kegiatan, pelaksana, keterangan
   - Pagination
   - Action buttons (Edit, Hapus)

2. **Tambah Program Kerja**

   - Form lengkap dengan validasi
   - Upload dokumen output
   - Format angka Rupiah

3. **Edit Program Kerja**

   - Update data existing
   - Ganti dokumen output
   - Validasi input

4. **Hapus Program Kerja**

   - Konfirmasi sebelum hapus
   - Hapus file dokumen terkait

5. **Download Dokumen**
   - Download dokumen output yang di-upload

### 🎨 Desain

- ✅ Formal & Professional (sesuai standar pemerintahan)
- ✅ Monochrome color scheme
- ✅ Responsive design
- ✅ Clean & minimal interface
- ✅ Smooth transitions & animations

---

## 🔧 Troubleshooting

### Error: Database Connection Failed

**Solusi**:

1. Pastikan MySQL/MariaDB sudah berjalan
2. Cek kredensial database di file `.env`
3. Pastikan database sudah dibuat
4. Test koneksi dengan command: `php spark db:table`

### Error: File Upload Failed

**Solusi**:

1. Pastikan folder `writable/uploads/dokumen_output` ada dan writable
2. Cek permission folder: `chmod 755 writable/uploads/dokumen_output`
3. Cek `php.ini` untuk `upload_max_filesize` dan `post_max_size`

### Error: Routes Not Found

**Solusi**:

1. Pastikan file `app/Config/Routes.php` sudah dikonfigurasi
2. Clear cache: `php spark cache:clear`
3. Restart server development

---

## 📊 Database Schema

### Tabel: `program_kerja`

| Kolom              | Tipe          | Keterangan                  |
| ------------------ | ------------- | --------------------------- |
| id                 | INT(11)       | Primary Key, Auto Increment |
| nama_kegiatan      | VARCHAR(500)  | Nama kegiatan (required)    |
| rencana_kegiatan   | TEXT          | Rencana detail kegiatan     |
| anggaran           | DECIMAL(15,2) | Anggaran (required)         |
| realisasi_kegiatan | TEXT          | Deskripsi realisasi         |
| pelaksana          | VARCHAR(255)  | Nama pelaksana/PIC          |
| dokumen_output     | VARCHAR(255)  | Path file dokumen           |
| realisasi_anggaran | DECIMAL(15,2) | Realisasi anggaran          |
| sasaran_strategis  | TEXT          | Sasaran strategis           |
| keterangan         | TEXT          | Keterangan tambahan         |
| created_at         | DATETIME      | Tanggal dibuat              |
| updated_at         | DATETIME      | Tanggal diupdate            |

---

## 🔐 Keamanan

Fitur keamanan yang sudah diimplementasikan:

- ✅ CSRF Protection (CodeIgniter built-in)
- ✅ XSS Prevention (auto-escaping)
- ✅ SQL Injection Prevention (Query Builder)
- ✅ File Upload Validation (type & size)
- ✅ Input Sanitization

---

## 📞 Bantuan

Jika mengalami kendala, silakan hubungi tim development atau buat issue di repository project.

---

**Dibuat oleh**: PKPT Development Team  
**Tanggal**: 8 Januari 2026  
**Versi**: 1.0.0
