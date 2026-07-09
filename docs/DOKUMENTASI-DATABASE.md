# Dokumentasi Database Day 8 — Portfolio dengan MySQL CRUD & File Upload

## Daftar Isi
1. [Database & Tabel](#1-database--tabel)
2. [connection.php](#2-connectionphp)
3. [Tampilkan Data dari Database (SELECT)](#3-tampilkan-data-dari-database-select)
4. [Form Input + Upload File (INSERT)](#4-form-input--upload-file-insert)
5. [Validasi Input & File](#5-validasi-input--file)
6. [Hapus Data (DELETE + unlink)](#6-hapus-data-delete--unlink)
7. [Edit Data (UPDATE — Bonus)](#7-edit-data-update--bonus)
8. [Download File](#8-download-file)
9. [Struktur Folder](#9-struktur-folder)
10. [Menjalankan di Local Server](#10-menjalankan-di-local-server)
11. [Deployment ke Free Hosting](#11-deployment-ke-free-hosting)
12. [Ringkasan untuk Presentasi ke Mentor](#12-ringkasan-untuk-presentasi-ke-mentor)

---

## 1. Database & Tabel

### Database: `portfolio_db`

### Tabel: `projects`

```sql
CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  tech TEXT NOT NULL,
  category VARCHAR(50) NOT NULL,
  file_path VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Penjelasan Kolom:

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| `id` | INT (AUTO_INCREMENT) | Nomor unik setiap project. Otomatis naik setiap insert. |
| `title` | VARCHAR(255) | Judul project (wajib diisi). |
| `description` | TEXT | Deskripsi panjang (wajib diisi). |
| `tech` | TEXT | Tech stack disimpan sebagai **JSON string**. Contoh: `["Laravel","PHP","MySQL"]` |
| `category` | VARCHAR(50) | Salah satu: `Backend`, `Frontend`, `Fullstack` |
| `file_path` | VARCHAR(255) | Path file upload (boleh NULL). Contoh: `uploads/1234567890_project.pdf` |
| `created_at` | TIMESTAMP | Otomatis terisi waktu insert. |

### Kenapa `tech` pakai TEXT (JSON)?

Tech stack adalah array (misal: `Laravel, PHP, MySQL`). Relational database tidak bisa langsung simpan array. Solusinya:
1. Di PHP, array diubah ke JSON string dengan `json_encode()`
2. Disimpan ke kolom TEXT
3. Saat dibaca, diubah balik ke array dengan `json_decode()`

**Pertanyaan mentor:** "Kenapa kolom `tech` tidak pakai tabel relasi (many-to-many) saja?"
**Jawaban:** Bisa saja pakai tabel `project_tech` + `technologies`. Tapi untuk skala kecil/portofolio, simpan sebagai JSON lebih sederhana — tidak perlu JOIN table, cukup encode/decode. Untuk aplikasi besar, relasi lebih baik karena bisa query langsung (e.g., "cari semua project pakai Laravel").

---

## 2. connection.php

```php
<?php
$host = 'localhost';
$db = 'portfolio_db';
$user = 'root';
$pass = 'admin123';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
```

### Cara Kerja:

1. **`new mysqli(host, user, pass, db)`** — Membuat koneksi ke MySQL server
   - `localhost` → server di komputer sendiri
   - `root` / `admin123` → username dan password MySQL
   - `portfolio_db` → nama database yang dipakai

2. **`$conn->connect_error`** — Jika koneksi gagal (salah password, server mati, dll), program berhenti dengan pesan error.

3. **File ini di-include** di halaman lain dengan `require_once`:
   ```php
   require_once __DIR__ . '/connection.php';
   ```
   Variabel `$conn` bisa dipakai di mana saja setelah include.

### Credential untuk Deployment:

| Environment | Host | User | Pass | DB |
|------------|------|------|------|-----|
| Local | `localhost` | `root` | `admin123` | `portfolio_db` |
| InfinityFree | `sqlXXX.infinityfree.com` | `if0_XXXXXX` | (dari panel) | `if0_XXXXXX_portfolio_db` |
| 000WebHost | `localhost` | `idXXXXXX_XXXX` | (dari panel) | `idXXXXXX_XXXX_portfolio_db` |

**Pertanyaan mentor:** "Apa fungsi `require_once`? Bedanya dengan `include`?"
**Jawaban:** `require_once` menyisipkan file dan menjalankannya. Jika file tidak ditemukan, script **berhenti** (fatal error). `include` hanya memberi warning, script lanjut. `_once` memastikan file tidak di-include dua kali. Untuk file kritis seperti koneksi database, pakai `require_once`.

---

## 3. Tampilkan Data dari Database (SELECT)

### Di `index.php` (halaman utama portfolio):

```php
require_once __DIR__ . '/connection.php';
$result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = [];
while ($row = $result->fetch_assoc()) {
  $tech = json_decode($row['tech'], true);
  if (!is_array($tech)) $tech = [];
  $projects[] = [
    "title"     => $row['title'],
    "desc"      => $row['description'],
    "tech"      => $tech,
    "link"      => $row['file_path'] ? $row['file_path'] : "#",
    "icon"      => "📁",
    "category"  => $row['category'],
    "file_path" => $row['file_path']
  ];
}
```

### Alur:

1. **`$conn->query("SELECT ...")`** — Kirim query ke MySQL
2. **`$result->fetch_assoc()`** — Ambil satu baris sebagai array asosiatif (key = nama kolom)
3. **Loop `while`** — Ambil semua baris sampai habis
4. **`json_decode($row['tech'], true)`** — Ubah JSON string ke array PHP
5. **Simpan ke `$projects[]`** — Format yang sama seperti sebelumnya, jadi fungsi `renderProjectCard()` tidak perlu diubah

### Di `admin/projects.php` (halaman daftar project):

```php
$result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
// Tampilkan dalam tabel HTML
while ($row = $result->fetch_assoc()):
  // Setiap kolom ditampilkan ke tabel
endwhile;
```

Plus ada fitur **pagination sederhana**: parameter `success` dan `error` di URL untuk menampilkan notifikasi setelah redirect.

```php
if (isset($_GET['success'])): ?>
  <div class="... text-green-400"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>
```

### Di `admin/index.php` (dashboard):

```php
$projectCount = $conn->query("SELECT COUNT(*) as total FROM projects")->fetch_assoc()['total'];
// Hitung project per kategori untuk Chart.js
$categoryCount = $conn->query("SELECT category, COUNT(*) as total FROM projects GROUP BY category");
```

Dashboard menampilkan:
- Total project
- Jumlah kategori
- Project yang punya file
- Pie chart distribusi kategori
- Bar chart per kategori

Semua data **real-time** — tiap halaman di-refresh, query ulang ke database.

**Pertanyaan mentor:** "Apa itu SQL injection? Apakah kode ini aman?"
**Jawaban:** Pada SELECT biasa seperti `$conn->query("SELECT * FROM projects")`, tidak ada input user jadi aman. Tapi kalau ada filter/search dengan input user, wajib pakai **prepared statement** (seperti yang dipakai di INSERT, UPDATE, DELETE). Prepared statement memisahkan query dari data, jadi data tidak bisa dimanipulasi.

---

## 4. Form Input + Upload File (INSERT)

### File: `admin/add_project.php`

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $tech_raw = trim($_POST['tech'] ?? '');
  $category = trim($_POST['category'] ?? '');

  // validasi...

  $tech = json_encode(array_map('trim', explode(',', $tech_raw)));
  $file_path = null;

  // upload file...
  // validasi file...

  $stmt = $conn->prepare("INSERT INTO projects (title, description, tech, category, file_path) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $title, $description, $tech, $category, $file_path);
  $stmt->execute();
  $stmt->close();
  $conn->close();
  header('Location: projects.php?success=Project berhasil ditambahkan');
  exit;
}
```

### Alur:

1. **Cek method POST** — Form hanya diproses jika method POST
2. **Ambil data** dengan `$_POST`, bersihkan dengan `trim()`
3. **Simpan tech stack** — User input `Laravel, PHP, MySQL`, dipecah dengan `explode(',')`, dibersihkan, lalu di-encode ke JSON
4. **Upload file** (opsional) — Diproses dengan validasi sendiri (lihat section 5)
5. **Insert ke database** — Pakai **prepared statement**
6. **Redirect** ke halaman list dengan pesan sukses

### Prepared Statement:

```php
$stmt = $conn->prepare("INSERT INTO projects (...) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $title, $description, $tech, $category, $file_path);
$stmt->execute();
```

- **`prepare()`** — MySQL menyiapkan query dengan placeholder `?`
- **`bind_param()`** — Mengikat variabel ke placeholder
  - `"sssss"` — 5 parameter, semuanya string
  - Parameter types: `s` = string, `i` = integer, `d` = double, `b` = blob
- **`execute()`** — Eksekusi query dengan data yang sudah diikat

### Kenapa Prepared Statement?

Keamanan! Jika user mengetik judul `'; DROP TABLE projects; --`, tanpa prepared statement query jadi:
```sql
INSERT INTO projects (title) VALUES (''; DROP TABLE projects; --')
```
Dengan prepared statement, input diperlakukan sebagai **data**, bukan bagian query. Aman.

### Data Flow:

```
User input (koma)  →  explode(',')  →  array  →  json_encode()  →  JSON string  →  MySQL TEXT
Contoh: "Laravel,PHP,MySQL"  →  ["Laravel","PHP","MySQL"]  →  '["Laravel","PHP","MySQL"]'
```

**Pertanyaan mentor:** "Apa bedanya `$_POST` dengan `$_GET`?"
**Jawaban:** `$_POST` ambil data dari body HTTP request (untuk form dengan method POST, biasanya untuk mengubah data). `$_GET` ambil data dari URL (query string). POST lebih aman untuk data sensitif karena tidak muncul di URL. Untuk menambah/ mengubah/ menghapus data, selalu pakai POST.

---

## 5. Validasi Input & File

### Validasi Input:

```php
if (empty($title)) $errors[] = 'Title wajib diisi.';
if (empty($description)) $errors[] = 'Description wajib diisi.';
if (empty($tech_raw)) $errors[] = 'Tech stack wajib diisi.';
if (!in_array($category, ['Backend', 'Frontend', 'Fullstack'])) $errors[] = 'Pilih category yang valid.';
```

### Validasi File Upload:

```php
// Cek apakah ada file diupload (boleh kosong)
if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
  // Cek error upload
  if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $errors[] = 'Error upload file.';
  } else {
    // Cek tipe file
    $allowed = ['pdf', 'jpg', 'png'];
    $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
      $errors[] = 'Tipe file harus PDF, JPG, atau PNG.';
    }
    // Cek ukuran (maks 2MB)
    elseif ($_FILES['file']['size'] > 2 * 1024 * 1024) {
      $errors[] = 'Ukuran file maksimal 2MB.';
    } else {
      // Simpan file
      $upload_dir = __DIR__ . '/../uploads/';
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }
      $filename = time() . '_' . basename($_FILES['file']['name']);
      $dest = $upload_dir . $filename;
      if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
        $file_path = 'uploads/' . $filename;
      }
    }
  }
}
```

### 3 Lapis Validasi File:

| Lapisan | Cek | Tujuan |
|---------|-----|--------|
| 1. Ada file? | `error !== UPLOAD_ERR_NO_FILE` | File opsional, skip jika tidak ada |
| 2. Upload sukses? | `error === UPLOAD_ERR_OK` | Pastikan tidak ada error saat upload |
| 3. Tipe & ukuran | Ekstensi + size | Hanya terima PDF/JPG/PNG, maks 2MB |

### Naming File:

```php
$filename = time() . '_' . basename($_FILES['file']['name']);
// Hasil: "1689012345_project-report.pdf"
```

Pakai `time()` untuk menghindari nama file duplikat.

### Penyimpanan:

File disimpan di `uploads/` (relatif ke root project). Path yang disimpan ke database: `uploads/1689012345_project-report.pdf`.

### Tampilkan Error:

```php
<?php if (!empty($errors)): ?>
  <div class="... bg-red-500/10 border border-red-500/30 rounded-lg">
    <?php foreach ($errors as $e): ?>
      <p class="text-red-400 text-sm"><?php echo htmlspecialchars($e); ?></p>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
```

Error tampil di atas form. Form tetap terisi data sebelumnya (`value` pakai `$title ?? ''`).

**Pertanyaan mentor:** "Apa itu `XSS` dan bagaimana kode ini mencegahnya?"
**Jawaban:** XSS (Cross-Site Scripting) adalah serangan dengan menyisipkan skrip jahat. Kode ini mencegah dengan **`htmlspecialchars()`** — fungsi yang mengubah karakter khusus HTML (`<`, `>`, `&`, `"`) menjadi entitas HTML (`&lt;`, `&gt;`, dll). Jadi jika user mengetik `<script>alert('xss')</script>`, akan tampil sebagai teks biasa, bukan dijalankan browser.

---

## 6. Hapus Data (DELETE + unlink)

### File: `admin/delete_project.php`

```php
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
  header('Location: projects.php?error=Invalid request');
  exit;
}

$id = (int)$_POST['id'];

// Ambil file_path dulu sebelum hapus
$stmt = $conn->prepare("SELECT file_path FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

// Hapus file dari server jika ada
if ($project['file_path']) {
  $file = __DIR__ . '/../' . $project['file_path'];
  if (file_exists($file)) {
    unlink($file);
  }
}

// Hapus data dari database
$stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header('Location: projects.php?success=Project berhasil dihapus');
```

### Alur:

1. **Cek method POST** — Hanya bisa lewat form POST (tidak bisa akses langsung URL)
2. **Ambil data project dulu** — Kita perlu `file_path` untuk hapus file fisik
3. **Hapus file fisik** — `unlink()` menghapus file dari folder `uploads/`
4. **Hapus database** — `DELETE FROM projects WHERE id = ?`

### Kenapa SELECT dulu sebelum DELETE?

Jika langsung DELETE, kita tidak tahu file_path-nya. File akan jadi "sampah" (orphan file) — ada di server tapi tidak ada di database.

```php
// Urutan yang benar:
// 1. SELECT file_path
// 2. unlink(file_path)
// 3. DELETE FROM projects
```

### Tombol Hapus dengan Konfirmasi:

```php
<form action="delete_project.php" method="POST" onsubmit="return confirm('Yakin hapus project ini?');">
  <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
  <button type="submit">Hapus</button>
</form>
```

JavaScript `confirm()` akan muncul sebelum form dikirim. Jika user klik "Cancel", form batal dikirim.

**Pertanyaan mentor:** "Apa fungsi `unlink()`?"
**Jawaban:** `unlink()` adalah fungsi PHP untuk menghapus file dari sistem file server. Mirip seperti hapus file manual di file manager. Kalau file tidak ada, `file_exists()` mencegah error. Penting: hapus file dulu baru hapus database, supaya tidak ada file sampah.

---

## 7. Edit Data (UPDATE — Bonus)

### File: `admin/edit_project.php?id=XXX`

#### 1. Ambil data lama:

```php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

if (!$project) {
  header('Location: projects.php?error=Project tidak ditemukan');
  exit;
}
```

#### 2. Update data:

```php
$tech = json_encode(array_map('trim', explode(',', $tech_raw)));
$file_path = $project['file_path']; // default: pakai file lama

// Jika upload file baru
if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
  // Hapus file lama
  if ($project['file_path']) {
    $old = __DIR__ . '/../' . $project['file_path'];
    if (file_exists($old)) unlink($old);
  }
  // Upload file baru (sama seperti add_project)
  // ...
  $file_path = 'uploads/' . $filename;
}

// UPDATE database
$stmt = $conn->prepare("UPDATE projects SET title=?, description=?, tech=?, category=?, file_path=? WHERE id=?");
$stmt->bind_param("sssssi", $title, $description, $tech, $category, $file_path, $id);
$stmt->execute();
```

### Perbedaan dengan INSERT:

| Aspek | INSERT | UPDATE |
|-------|--------|--------|
| Query | `INSERT INTO ... VALUES (?)` | `UPDATE ... SET ... WHERE id=?` |
| bind_param | `"sssss"` (5 param) | `"sssssi"` (6 param — terakhir `i` untuk id) |
| File lama | Tidak ada | Perlu hapus file lama dulu |
| Default file | `null` | Pakai file_path existing |

### File Lama — Handle Khusus:

```php
// Jika user pilih file baru → hapus yang lama, simpan yang baru
// Jika user tidak pilih file → pertahankan file_path yang lama
$file_path = $project['file_path']; // default: pakai file lama
```

### Tampilkan File Saat Ini di Form:

```php
<?php if ($project['file_path']): ?>
  <p class="text-gray-500 text-xs mt-2">
    File saat ini:
    <a href="../<?php echo $project['file_path']; ?>" class="text-purple-400 hover:underline">
      <?php echo basename($project['file_path']); ?>
    </a>
  </p>
<?php endif; ?>
```

### Form Terisi Otomatis:

```php
<input type="text" name="title"
       value="<?php echo htmlspecialchars($_POST['title'] ?? $project['title']); ?>" />
```

Prioritas: `$_POST` (data baru jika ada error validasi) > `$project` (data dari database).

**Pertanyaan mentor:** "Apa bedanya `GET` dan `POST` untuk operasi UPDATE?"
**Jawaban:** `GET` untuk mengambil data lama (read-only), `POST` untuk mengirim data baru. Di edit_project.php, `$_GET['id']` untuk mengambil data yang akan diedit, `$_POST` untuk mengirim perubahan. Tidak ada data sensitif di `$_GET` karena hanya berisi ID numerik.

---

## 8. Download File

### Di halaman utama `index.php`:

```php
function renderProjectCard(...) {
  // ...
  if ($file_path) {
    $downloadLink = '<a href="' . $file_path . '" download class="text-green-400 hover:text-green-300 transition-colors text-sm">📎 Download →</a>';
  }
  return '...' . $downloadLink . '...';
}
```

Atribut `download` di tag `<a>` memberitahu browser untuk mengunduh file, bukan membukanya.

### Di admin `projects.php`:

```php
<?php if ($row['file_path']): ?>
  <a href="../<?php echo $row['file_path']; ?>" class="text-purple-400 hover:text-purple-300 transition-colors" download>📎 Download</a>
<?php else: ?>
  <span class="text-gray-600">—</span>
<?php endif; ?>
```

Path di admin perlu `../` karena file admin ada di dalam folder `admin/`.

### Cara Kerja:

1. Path yang disimpan di database: `uploads/1689012345_project.pdf`
2. Link download: `href="uploads/1689012345_project.pdf"`
3. Browser meminta file ke server
4. Server mengirim file (karena file PHP bisa akses file statis)
5. Browser mengunduh (karena ada atribut `download`)

**Pertanyaan mentor:** "Apa bedanya link download dengan link biasa?"
**Jawaban:** Link biasa (`<a href="file.pdf">`) akan membuka file di browser (PDF viewer). Link download (`<a href="file.pdf" download>`) akan langsung mengunduh file ke komputer. Atribut `download` bisa juga dikombinasi dengan PHP header untuk proteksi (hanya user tertentu yang bisa download).

---

## 9. Struktur Folder

```
projek-portofolio/
├── connection.php              # Koneksi database (di-include semua halaman)
├── index.php                   # Halaman utama portfolio (dinamis dari DB)
│
├── admin/                      # Halaman admin (CRUD)
│   ├── sidebar.php             # Layout admin (navigasi, header, footer)
│   ├── index.php               # Dashboard — statistik dari database
│   ├── projects.php            # Daftar semua project (tabel)
│   ├── add_project.php         # Form tambah project + upload file
│   ├── edit_project.php        # Form edit project + ganti file
│   └── delete_project.php      # Hapus project + file (via POST)
│
├── css/
│   └── style.css               # Styling tambahan (di luar Tailwind)
│
├── js/
│   ├── script.js               # JavaScript interaksi (mobile menu, form)
│   └── dashboard.js            # Chart.js untuk dashboard
│
├── pages/
│   └── dashboard.html          # Halaman dashboard statis (sebelum PHP)
│
├── assets/                     # Asset visual
│
├── uploads/                    # File upload (auto-created)
│   ├── 1689012345_project.pdf
│   └── 1689012346_diagram.png
│
└── docs/
    └── DOKUMENTASI-DATABASE.md # Dokumentasi ini
```

### Perubahan dari Day 7:

| File | Keterangan |
|------|------------|
| `connection.php` | **BARU** — Koneksi ke MySQL |
| `admin/` | **BARU** — Folder untuk halaman CRUD |
| `uploads/` | **BARU** — Folder untuk file upload (otomatis dibuat) |
| `index.php` | **DIUPDATE** — Data project dari database, bukan hardcode array |
| `css/style.css` | **DIUPDATE** — Style untuk halaman admin |

### File yang Tidak Berubah:

`assets/`, `js/script.js`, `js/dashboard.js`, `pages/dashboard.html`

**Pertanyaan mentor:** "Apa itu `__DIR__` dan fungsinya?"
**Jawaban:** `__DIR__` adalah konstanta PHP yang berisi path direktori tempat file tersebut berada. Contoh: di `/admin/add_project.php`, `__DIR__` = `/.../admin/`. Jadi `__DIR__ . '/../connection.php'` berarti naik satu level (ke folder project) lalu akses `connection.php`. Ini penting karena relative path berbeda tergantung dari mana file di-include.

---

## 10. Menjalankan di Local Server

### Prasyarat:

1. **XAMPP / Laragon / MAMP** — Web server dengan PHP + MySQL
2. **MySQL** — Database server

### Langkah:

#### 1. Start MySQL & Apache

Buka XAMPP/XAMPP Control → Start **Apache** dan **MySQL**.

#### 2. Buat Database & Tabel

Buka **phpMyAdmin** (`http://localhost/phpmyadmin`) atau terminal:

```sql
CREATE DATABASE portfolio_db;
USE portfolio_db;

CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  tech TEXT NOT NULL,
  category VARCHAR(50) NOT NULL,
  file_path VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### 3. Copy Project

Letakkan folder project di `C:\xampp\htdocs\projek-portofolio\` (Windows) atau `/opt/lampp/htdocs/projek-portofolio/` (Linux).

#### 4. Sesuaikan connection.php

Pastikan credential sesuai:
```php
$host = 'localhost';
$db = 'portfolio_db';
$user = 'root';
$pass = '';        // XAMPP: string kosong
// $pass = 'admin123';  // jika pakai password
```

#### 5. Akses

- Portfolio: `http://localhost/projek-portofolio/index.php`
- Admin dashboard: `http://localhost/projek-portofolio/admin/index.php`
- Admin project list: `http://localhost/projek-portofolio/admin/projects.php`

#### 6. Cek di Terminal (Debug)

```bash
cd "C:\xampp\htdocs\projek-portofolio"
php -S localhost:8000
```

Akses: `http://localhost:8000/index.php`

**Pertanyaan mentor:** "Apa itu localhost?"
**Jawaban:** `localhost` adalah alamat loopback yang merujuk ke komputer sendiri. `localhost:8000` artinya server PHP berjalan di port 8000 di komputer yang sama. Untuk akses dari perangkat lain di jaringan, pakai IP lokal (contoh: `192.168.1.5:8000`).

---

## 11. Deployment ke Free Hosting

### Pilihan Hosting Gratis:

| Hosting | Panel | MySQL | phpMyAdmin | FTP |
|---------|-------|-------|------------|-----|
| InfinityFree | Custom panel | ✅ (via panel) | ✅ | ✅ |
| 000WebHost | cPanel-like | ✅ | ✅ | ✅ |
| AwardSpace | Custom panel | ✅ | ✅ | ✅ |

### Langkah Deployment (InfinityFree / 000WebHost):

#### Langkah 1: Export SQL

Buka phpMyAdmin lokal → Pilih database `portfolio_db` → Tab **Export** → Pilih **Quick** → **Go**.

Simpan file `portfolio_db.sql`.

#### Langkah 2: Upload File via FTP

**Tools FTP:** FileZilla, WinSCP, atau web browser.

```
Host:     ftp.infinityfree.com / atau sesuaikan
User:     if0_XXXXXX
Pass:     (dari panel)
Port:     21
```

Upload semua file dan folder project (kecuali folder `node_modules`, `.git`, dll) ke folder `htdocs/` atau `public_html/`.

#### Langkah 3: Update connection.php

```php
// InfinityFree
$host = 'sqlXXX.infinityfree.com';  // dari panel
$db   = 'if0_XXXXXX_portfolio_db';  // username_namadb
$user = 'if0_XXXXXX';
$pass = 'password_dari_panel';

// 000WebHost
$host = 'localhost';
$db   = 'idXXXXXX_XXXX_portfolio_db';
$user = 'idXXXXXX_XXXX';
$pass = 'password_dari_panel';
```

#### Langkah 4: Import SQL via phpMyAdmin

1. Login ke hosting panel
2. Buka **phpMyAdmin**
3. Pilih database (yang sudah dibuat atau buat baru)
4. Tab **Import**
5. Pilih file `portfolio_db.sql`
6. **Go**

#### Langkah 5: Test

- Buka `https://namasite.infinityfreeapp.com/index.php`
- Cek Admin: `https://namasite.infinityfreeapp.com/admin/projects.php`
- Tambah project baru
- Upload file

### Troubleshooting:

| Masalah | Solusi |
|---------|--------|
| "Connection failed" | Cek credential di connection.php — host, user, pass, db name |
| "Database not selected" | Pastikan database sudah dibuat dan nama sesuai |
| File upload error | Cek folder `uploads/` — mungkin perlu chmod 755 |
| Blank page | Aktifkan error reporting: tambahkan `error_reporting(E_ALL); ini_set('display_errors', 1);` di atas connection.php |
| 404 Not Found | Pastikan file terupload dengan benar, case-sensitive di Linux |

**Pertanyaan mentor:** "Apa perbedaan hosting gratis dan berbayar untuk PHP MySQL?"
**Jawaban:** Hosting gratis biasanya memiliki keterbatasan: resource CPU terbatas, database terbatas (1-2 MB), muncul iklan, tidak ada SSL/HTTPS (kecuali InfinityFree yang sudah include SSL), dan server lebih lambat. Untuk portofolio/ belajar, hosting gratis cukup. Untuk aplikasi produksi, hosting berbayar lebih stabil.

---

## 12. Ringkasan untuk Presentasi ke Mentor

### Checklist Fitur:

| Kriteria | Status | Lokasi di File |
|----------|--------|----------------|
| Database MySQL | ✅ | `portfolio_db` — tabel `projects` (6 kolom) |
| Koneksi dengan `connection.php` | ✅ | `connection.php:7` — `new mysqli()` |
| SELECT dari database | ✅ | `index.php:28` — `$conn->query("SELECT * FROM projects")` |
| INSERT ke database | ✅ | `add_project.php:49` — prepared statement INSERT |
| UPDATE database (Bonus) | ✅ | `edit_project.php:62` — prepared statement UPDATE |
| DELETE dari database | ✅ | `delete_project.php:28` — prepared statement DELETE |
| Upload file (PDF/JPG/PNG) | ✅ | `add_project.php:22-45` — validasi + `move_uploaded_file()` |
| Hapus file fisik saat DELETE | ✅ | `delete_project.php:21-25` — `unlink()` |
| Ganti file saat UPDATE | ✅ | `edit_project.php:44-46` — hapus lama + upload baru |
| Validasi input (server-side) | ✅ | `add_project.php:14-17` — empty check + category |
| Validasi file (tipe & ukuran) | ✅ | `add_project.php:26-31` — ekstensi & max 2MB |
| Prepared statement (anti SQL injection) | ✅ | Semua INSERT/UPDATE/DELETE pakai `prepare()` + `bind_param()` |
| `htmlspecialchars()` (anti XSS) | ✅ | Semua output data dinamis |
| Download file dari halaman utama | ✅ | `index.php:84` — atribut `download` |
| Dashboard admin dengan Chart.js | ✅ | `admin/index.php` — data real-time dari database |
| JSON untuk tech stack | ✅ | `json_encode()` simpan, `json_decode()` baca |
| Redirect setelah operasi CRUD | ✅ | `header('Location: ...')` setelah sukses |
| Error messages di form | ✅ | Array `$errors` ditampilkan di atas form |

### Cara Menjawab Pertanyaan Mentor:

**"Apa yang kamu pelajari dari tugas ini?"**

> Saya belajar menghubungkan website portfolio ke database MySQL. Data project tidak lagi hardcode di array PHP, tapi disimpan di database. Saya juga belajar CRUD lengkap — Create (INSERT), Read (SELECT), Update (UPDATE), Delete (DELETE) — plus upload file. Yang paling penting, saya belajar prepared statement untuk keamanan, dan json_encode/decode untuk menyimpan array di database.

**"Apa tantangan terbesar?"**

> Memahami prepared statement dan kenapa lebih aman dari query biasa. Awalnya saya pikir query dengan concatenation (`.$variable.`) lebih sederhana. Tapi setelah belajar SQL injection, saya paham kenapa prepared statement itu wajib — data dan query dipisah, jadi input user tidak bisa menjadi bagian dari query.

**"Apa bedanya query biasa dengan prepared statement?"**

> Query biasa: `$conn->query("INSERT INTO projects (title) VALUES ('$title')")` — variabel langsung digabung ke SQL. Kalau `$title` berisi `'; DROP TABLE --`, database bisa dihancurkan. Prepared statement: `$stmt->bind_param("s", $title)` — MySQL tahu bahwa `$title` adalah data, bukan query. Aman.

**"Apa itu SQL injection? Contoh sederhana?"**

> SQL injection adalah serangan dengan menyisipkan perintah SQL berbahaya melalui input form. Contoh: di form login, attacker mengetik username `admin' OR '1'='1`. Query jadi: `SELECT * FROM users WHERE username = 'admin' OR '1'='1'` — mengembalikan semua user, login berhasil tanpa password. Prepared statement mencegah ini.

**"Kenapa tech stack disimpan sebagai JSON, bukan tabel terpisah?"**

> Untuk aplikasi portofolio, JSON lebih praktis dan sederhana. Tidak perlu JOIN table, cukup encode/decode. Tapi untuk aplikasi skala besar (misal: marketplace skill), tabel terpisah lebih baik karena bisa query spesifik (e.g., "cari project dengan Laravel") dan lebih mudah diindex.

**"Apa fungsi `unlink()` di kode hapus?"**

> `unlink()` menghapus file fisik dari server. Saat project dihapus, file upload-nya (misal PDF laporan) juga harus dihapus agar tidak menumpuk jadi sampah. Urutannya: SELECT file_path → unlink file → DELETE database. Kalau DELETE dulu, kita kehilangan informasi file_path dan file jadi sampah.

**"Apa langkah-langkah deployment ke hosting?"**

> 1. Export SQL dari phpMyAdmin lokal
> 2. Upload semua file project via FTP ke folder `public_html/` atau `htdocs/`
> 3. Update `connection.php` dengan credential hosting (host, user, pass, db name berbeda)
> 4. Import SQL via phpMyAdmin hosting
> 5. Test akses semua halaman
