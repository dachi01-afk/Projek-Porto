# Database Day 8 — Portfolio with MySQL CRUD, File Upload, Admin Sidebar

## 1. Tujuan
Meng-upgrade portfolio statis (PHP + array hardcoded) menjadi aplikasi dinamis dengan database MySQL, CRUD operations, file upload, dan halaman admin sidebar.

## 2. Database

### Database: `portfolio_db`

### Table: `projects`

| Column       | Type         | Extra                   |
|-------------|-------------|------------------------|
| id          | INT          | PRIMARY KEY, AUTO_INCREMENT |
| title       | VARCHAR(255) | NOT NULL               |
| description | TEXT         | NOT NULL               |
| tech        | TEXT         | NOT NULL (JSON format) |
| category    | VARCHAR(50)  | NOT NULL               |
| file_path   | VARCHAR(255) | NULL                   |
| created_at  | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP |

### Connection: `connection.php`
- File di root project (bukan di admin/)
- Koneksi `mysqli` — host: `localhost`, user: `root`, pass: `admin123`, db: `portfolio_db`
- Di-include via `require_once(__DIR__ . '/connection.php')` di setiap halaman yang perlu DB

## 3. Struktur Folder Baru

```
projek-portofolio/
├── connection.php               # [NEW] Koneksi DB
├── index.php                    # [MODIFIED] Ganti array → SELECT query
├── admin/
│   ├── sidebar.php              # [NEW] Sidebar navigation
│   ├── index.php                # [NEW] Dashboard (Chart.js)
│   ├── projects.php             # [NEW] Table + Hapus + Download
│   ├── add_project.php          # [NEW] Form + Upload
│   ├── edit_project.php         # [NEW] Form edit (bonus)
│   └── delete_project.php       # [NEW] Logic DELETE
├── uploads/                     # [NEW] Folder file project
├── css/style.css
├── js/
│   ├── script.js
│   └── dashboard.js
├── pages/dashboard.html         # [REMOVED] Digantikan admin/index.php
├── assets/
└── docs/
```

## 4. Halaman Admin — Detail

### 4.1 `admin/sidebar.php`
- Include di setiap halaman admin via `require_once()`
- Layout: sidebar kiri tetap (250px), konten di kanan
- Menu:
  - 📊 Dashboard → `admin/index.php`
  - 📋 Project List → `admin/projects.php`
  - ➕ Add Project → `admin/add_project.php`
- Link bawah: 🔙 Back to Portfolio → `../index.php`
- Style: gelap (senada dengan portfolio), hover effect

### 4.2 `admin/index.php` — Dashboard
- Sidebar + konten utama
- 2 chart dari Chart.js:
  - Stacked Bar: total effort/commit per project (data placeholder / dari DB nanti)
  - Polar Area: distribusi kategori project
- Judul "Admin Dashboard"
- Sama seperti `dashboard.html` sebelumnya tapi dalam layout sidebar

### 4.3 `admin/projects.php` — Project List
- Sidebar + tabel semua project
- Kolom tabel: No, Title, Category, File, Created At, Actions
- Actions: Edit (link), Hapus (form POST ke `delete_project.php`), Download (jika file ada)
- Data dari SELECT * FROM projects ORDER BY created_at DESC

### 4.4 `admin/add_project.php` — Add Project
- Sidebar + form
- Fields: Title (text), Description (textarea), Tech (text, comma-separated → JSON di PHP), Category (select: Backend/Frontend/Fullstack), File (file upload, optional)
- Validasi PHP:
  - All fields required (kecuali file)
  - File: only `.pdf`, `.jpg`, `.png` — max 2MB
  - Jika error: tampilkan error message di atas form
- Folder `uploads/` auto-created via `mkdir()` jika belum ada
- INSERT INTO projects, simpan `file_path`
- Redirect ke `projects.php` dengan success message

### 4.5 `admin/edit_project.php?id=X` — Edit Project (Bonus)
- Sidebar + form pre-filled dari DB
- Sama seperti add_project tapi UPDATE query
- Jika upload file baru, hapus file lama dari `uploads/` via `unlink()`

### 4.6 `admin/delete_project.php` — Delete Project
- Terima `id` via POST
- SELECT file_path, hapus file via `unlink()` jika ada
- DELETE FROM projects WHERE id = ?
- Redirect ke `projects.php`

## 5. Modifikasi `index.php` (Portfolio Publik)
- Hapus array `$projects` hardcoded
- Include `connection.php`
- Query: `SELECT * FROM projects ORDER BY created_at DESC`
- Simpan hasil ke array `$projects`, teruskan ke `renderPortfolioSection()`
- Tambah link Download di card project jika `file_path` tidak null
- Ubah `renderProjectCard()` — tambah parameter `$filePath` (default null)
- Navbar "Dashboard" hapus (pindah ke sidebar admin)

## 6. Uploads Folder
- Path: `uploads/` di root
- Auto-create via `mkdir($path, 0777, true)` di `add_project.php`
- File naming: `time() . '_' . basename($file['name'])` untuk hindari konflik
- Download link: `<a href="../uploads/{filename}">Download</a>`

## 7. Validasi
### Client-side (JS): required attribute, tipe file
### Server-side (PHP):
- `empty()` untuk required fields
- `$_FILES['file']['error'] === UPLOAD_ERR_OK`
- `$_FILES['file']['size'] <= 2 * 1024 * 1024`
- `in_array(pathinfo($filename, PATHINFO_EXTENSION), ['pdf', 'jpg', 'png'])`
- Error disimpan di `$errors` array, ditampilkan di form

## 8. Branching & Deployment
- Branch: `feature/database-day8`
- Tidak auto-push, tunggu konfirmasi
- Deployment guide disertakan di dokumentasi (InfinityFree / 000WebHost)

## 9. Dokumentasi
- File: `docs/DOKUMENTASI-DATABASE.md`
- Berisi penjelasan per kriteria penilaian agar user bisa jawab pertanyaan mentor

## 10. File yang tidak berubah
- `css/style.css` — tetap
- `js/script.js` — tetap
- `js/dashboard.js` — tetap
- `pages/dashboard.html` — dihapus (digantikan admin/index.php)
