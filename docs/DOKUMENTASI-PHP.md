# Dokumentasi PHP Portfolio - Bootcamp Day 7

## Daftar Isi
1. [Setup & Struktur File](#1-setup--struktur-file)
2. [Variabel PHP](#2-variabel-php)
3. [Array & Objek](#3-array--objek)
4. [Conditional (IF/ELSE)](#4-conditional-ifelse)
5. [Switch](#5-switch)
6. [Looping (Foreach)](#6-looping-foreach)
7. [Fungsi renderProjectCard()](#7-fungsi-renderprojectcard)
8. [Fungsi renderPortfolioSection() — Memanggil Fungsi Lain](#8-fungsi-renderportfoliosection--memanggil-fungsi-lain)
9. [Fungsi Rekursif renderSkillTree() (Bonus)](#9-fungsi-rekursif-renderskilltree-bonus)
10. [Menjalankan di Local Server](#10-menjalankan-di-local-server)

---

## 1. Setup & Struktur File

**File baru:**

| File | Fungsi |
|------|--------|
| `index.php` | Halaman utama portfolio dengan PHP (salinan dari `index.html`) |
| `DOKUMENTASI-PHP.md` | Dokumentasi ini |

**File yang tetap (tidak diubah):**
`index.html`, `style.css`, `script.js`, `dashboard.html`, `dashboard.js`

### Cara kerja PHP di sini:
PHP adalah bahasa **server-side**. Artinya:
1. Browser minta file `index.php`
2. Server (Apache) **mengeksekusi** kode PHP (mulai dari `<?php` sampai `?>`)
3. Hasilnya (HTML murni) dikirim ke browser

Jadi user tidak bisa melihat kode PHP — yang terlihat cuma output HTML-nya.

**Pertanyaan mentor:** "Apa bedanya file `.html` dengan `.php`?"
**Jawaban:** File `.html` langsung dikirim ke browser. File `.php` diproses dulu di server, baru hasilnya dikirim. PHP memungkinkan kita membuat halaman yang dinamis (isinya bisa berubah berdasarkan data/logika).

---

## 2. Variabel PHP

### Variabel yang digunakan:

```php
$nama = "Jimi Firgo Dakhi";         // string
$role = "Backend Developer";        // string
$open_to_work = true;               // boolean
$github = "https://github.com/..."; // string
$skills = [ /* ... */ ];            // array
$projects = [ /* ... */ ];          // array (multidimensi)
```

### Cara menampilkan variabel ke HTML:

```php
<!-- Cara 1: echo -->
<h1><?php echo $nama; ?></h1>

<!-- Cara 2: short echo tag -->
<h1><?= $nama ?></h1>
```

### 5+ variabel yang digunakan:
1. `$nama` — nama lengkap
2. `$role` — role/jabatan
3. `$bio_1` sampai `$bio_4` — 4 paragraf bio
4. `$github`, `$linkedin`, `$instagram`, `$email` — social links
5. `$foto`, `$foto_fallback` — URL foto
6. `$open_to_work` — status ketersediaan (boolean)

**Pertanyaan mentor:** "Apa perbedaan variabel lokal, global, superglobal di PHP?"
**Jawaban:**
- **Lokal**: dideklarasikan di dalam fungsi, hanya bisa diakses di fungsi itu.
- **Global**: dideklarasikan di luar fungsi, perlu keyword `global` untuk diakses di dalam fungsi.
- **Superglobal**: variabel bawaan PHP yang bisa diakses di mana saja — contoh: `$_GET`, `$_POST`, `$_SERVER`, `$_SESSION`.

---

## 3. Array & Objek

### Array Numerik (skill names):
```php
$skills = [
  ["name" => "PHP / Laravel", "level" => 92, "icon" => "🐘"],
  ["name" => "HTML & CSS", "level" => 85, "icon" => "🟢"]
];
```

### Array Asosiatif (project):
```php
$projects = [
  [
    "title" => "LMS Royal Prima",
    "desc" => "Description...",
    "tech" => ["Laravel", "PHP", "Blade", "MySQL"],
    "link" => "https://github.com/...",
    "category" => "Fullstack"
  ]
];
```

### Cara akses:
```php
echo $skills[0]["name"];            // "PHP / Laravel"
echo $projects[0]["tech"][1];       // "PHP"
```

**Pertanyaan mentor:** "Apa bedanya array dan objek di PHP?"
**Jawaban:** Array menyimpan data dengan key-value. Objek menyimpan data (properti) + fungsi (method) dalam satu kesatuan. Contoh objek: `$user = new User(); $user->getName();`.

---

## 4. Conditional (IF/ELSE)

### Badge "Open to Work"

```php
<?php if ($open_to_work): ?>
  <div class="... text-green-400">
    <span class="animate-pulse"></span>
    Open to Work 🟢
  </div>
<?php else: ?>
  <div class="... text-red-400">
    Not Available 🔴
  </div>
<?php endif; ?>
```

**Penjelasan:** Jika `$open_to_work` bernilai `true`, tampilkan badge hijau "Open to Work" dengan animasi pulse. Jika `false`, tampilkan badge merah "Not Available".

Cukup ubah satu baris di atas:
```php
$open_to_work = true;   // jadi hijau
$open_to_work = false;  // jadi merah
```

**Pertanyaan mentor:** "Apa bedanya IF/ELSE dan SWITCH?"
**Jawaban:** IF/ELSE cocok untuk kondisi dengan range atau perbandingan (contoh: `if ($nilai >= 80)`). SWITCH cocok untuk satu variabel dengan banyak kemungkinan nilai tetap (contoh: kategori project).

---

## 5. Switch

### Kategorisasi Project

```php
function getCategoryLabel($category) {
  switch ($category) {
    case "Backend":
      return '<span class="... bg-purple-500/20 text-purple-300">Backend</span>';
    case "Frontend":
      return '<span class="... bg-blue-500/20 text-blue-300">Frontend</span>';
    case "Fullstack":
      return '<span class="... bg-green-500/20 text-green-300">Fullstack</span>';
    default:
      return '<span class="... bg-gray-500/20 text-gray-300">Other</span>';
  }
}
```

**Data kategori per project:**
- LMS Royal Prima → **Fullstack**
- Overtime Request System → **Backend**
- SimpleAttendance → **Backend**
- Antrian-Ku → **Frontend**

Hasilnya: badge kategori muncul di pojok kanan atas setiap card project.

**Pertanyaan mentor:** "Kenapa menggunakan SWITCH, bukan IF/ELSE saja?"
**Jawaban:** SWITCH lebih rapi dan mudah dibaca saat memeriksa satu variabel terhadap banyak kemungkinan nilai. IF/ELSE jadi panjang dan bertumpuk kalau banyak kondisi.

---

## 6. Looping (Foreach)

### Foreach untuk Skills:
```php
<?php foreach ($skills as $index => $skill): ?>
  <div class="skill-card">
    <div class="text-4xl mb-3"><?= $skill["icon"] ?></div>
    <h3><?= htmlspecialchars($skill["name"]) ?></h3>
    <div class="progress-bar" style="width: <?= $skill["level"] ?>%"></div>
  </div>
<?php endforeach; ?>
```

**Keuntungan:** Untuk menambah/mengurangi skill, cukup edit array `$skills` — tidak perlu ubah HTML.

### Foreach untuk Projects (di dalam renderPortfolioSection):
```php
foreach ($items as $item) {
  $output .= renderProjectCard(
    $item["title"], $item["desc"],
    $item["tech"], $item["link"],
    $item["icon"], $item["category"]
  );
}
```

**Pertanyaan mentor:** "Macam-macam loop di PHP?"
**Jawaban:**
| Loop | Kapan dipakai |
|------|--------------|
| `for` | Jumlah iterasi sudah diketahui pasti |
| `while` | Iterasi selama kondisi true |
| `do while` | Sama seperti while, tapi minimal 1x jalan |
| `foreach` | Khusus untuk array (paling sering dipakai) |
| Nested loop | Loop di dalam loop (contoh: render tech badges) |

---

## 7. Fungsi renderProjectCard()

```php
function renderProjectCard($title, $desc, $tech, $link, $icon, $category) {
  // ... generate HTML card ...
  return $output;
}
```

**Parameter (5+):**
1. `$title` — judul project
2. `$desc` — deskripsi
3. `$tech` — array tech stack
4. `$link` — URL GitHub
5. `$icon` — emoji icon
6. `$category` — kategori (Backend/Frontend/Fullstack)

**Yang dilakukan fungsi:**
1. Loop `$tech` → generate badge HTML
2. Panggil `getCategoryLabel()` dengan SWITCH
3. Return string HTML lengkap satu card

---

## 8. Fungsi renderPortfolioSection() — Memanggil Fungsi Lain

```php
function renderPortfolioSection($title, $items) {
  $output = '<section>...<h2>' . $title . '</h2><div class="grid">';

  foreach ($items as $item) {
    // Memanggil fungsi lain di dalam fungsi
    $output .= renderProjectCard(
      $item["title"], $item["desc"],
      $item["tech"], $item["link"],
      $item["icon"], $item["category"]
    );
  }

  $output .= '</div></section>';
  return $output;
}
```

**Alur:**
```
renderPortfolioSection("Projects", $projects)
  └── foreach $projects
       └── renderProjectCard(title, desc, tech, link, icon, category)
            └── getCategoryLabel(category) — SWITCH
```

**Keuntungan:** Kode jadi modular — setiap fungsi punya 1 tanggung jawab. Kalau ada perubahan desain card, cukup edit `renderProjectCard()` saja.

**Pertanyaan mentor:** "Apa itu fungsi yang memanggil fungsi lain?"
**Jawaban:** Teknik memecah kode kompleks jadi fungsi-fungsi kecil. Fungsi induk memanggil fungsi anak. Ini membuat kode lebih rapi, mudah di-test, dan bisa digunakan ulang.

---

## 9. Fungsi Rekursif renderSkillTree() (Bonus)

```php
$skillTree = [
  "Web Development" => [
    "Frontend" => [
      "HTML & CSS" => ["Flexbox", "Grid", "Responsive Design"],
      "Tailwind CSS" => ["Utility Classes", "Responsive", "Custom Config"],
      "JavaScript" => ["DOM", "Events", "ES6+"]
    ],
    "Backend" => [
      "PHP" => ["Laravel" => ["Eloquent ORM", "Blade", "Routing", "Middleware"], "REST API"],
      "MySQL" => ["Query", "Relations", "Migrations"]
    ],
    "Tools" => ["Git & GitHub", "Composer", "VS Code"]
  ]
];

function renderSkillTree($tree, $depth = 0) {
  $output = '<ul style="padding-left: ' . ($depth * 20) . 'px">';

  foreach ($tree as $key => $value) {
    if (is_array($value)) {
      // Rekursif — panggil dirinya sendiri
      $output .= '<li>📂 ' . $key . '</li>';
      $output .= renderSkillTree($value, $depth + 1);
    } else {
      $output .= '<li>📄 ' . $value . '</li>';
    }
  }

  $output .= '</ul>';
  return $output;
}
```

**Cara kerja rekursif:**
1. Fungsi menerima array
2. Loop setiap item
3. Jika item adalah **array** → cetak sebagai folder → **panggil dirinya sendiri** dengan array tersebut
4. Jika item adalah **string** → cetak sebagai file (leaf node)
5. Berhenti saat semua item sudah string (tidak ada array lagi)

**Pertanyaan mentor:** "Apa itu fungsi rekursif?"
**Jawaban:** Fungsi yang memanggil dirinya sendiri. Berguna untuk data bertingkat/hierarki (seperti folder/file, skill tree, menu navigasi multi-level). Harus punya **base case** (kondisi berhenti) agar tidak infinite loop.

---

## 10. Menjalankan di Local Server

Karena PHP butuh server, jalankan dengan:

### Cara 1: Apache langsung
Arahkan Document Root Apache ke folder ini:
```
/home/jimi-firgo/Documents/maxy learn/projek-portofolio/
```
Lalu akses: `http://localhost/index.php`

### Cara 2: PHP Built-in Server
```bash
cd "/home/jimi-firgo/Documents/maxy learn/projek-portofolio"
php -S localhost:8000
```
Lalu akses: `http://localhost:8000/index.php`

### Cara 3: Cek di terminal (tanpa browser)
```bash
php index.php
```
Ini akan menampilkan output HTML di terminal (berguna untuk debug error).

---

## Ringkasan untuk Presentasi ke Mentor

| Kriteria | Status | Lokasi di `index.php` |
|----------|--------|----------------------|
| File .php bisa jalan di server | ✅ | Seluruh file |
| 5+ variabel PHP | ✅ | `$nama`, `$role`, `$bio_1..4`, `$github`, `$linkedin`, `$instagram`, `$email`, `$foto`, `$open_to_work` |
| Array skill (bukan hardcode) | ✅ | `$skills` — array asosiatif |
| IF/ELSE memengaruhi tampilan | ✅ | Badge "Open to Work" / "Not Available" |
| FOREACH render skill | ✅ | `foreach ($skills as $skill)` |
| FOREACH render project | ✅ | `foreach ($items as $item)` di `renderPortfolioSection()` |
| Fungsi renderProjectCard() 3+ param | ✅ | 6 parameter: `$title`, `$desc`, `$tech`, `$link`, `$icon`, `$category` |
| Fungsi panggil fungsi lain | ✅ | `renderPortfolioSection()` → `renderProjectCard()` → `getCategoryLabel()` |
| SWITCH kategori (bonus) | ✅ | `getCategoryLabel()` — Backend, Frontend, Fullstack, Other |
| Fungsi rekursif (bonus) | ✅ | `renderSkillTree()` — nested array → `<ul>` bertingkat |
| Tampilan tetap bagus | ✅ | Menggunakan `style.css` + Tailwind yang sama |
| Kode rapi & terstruktur | ✅ | Variabel di atas, fungsi di tengah, HTML di bawah |

### Cara menjawab jika mentor bertanya:

**"Apa yang kamu pelajari dari tugas ini?"**
> Saya belajar mengubah portfolio statis HTML menjadi dinamis dengan PHP. Data diri, skill, dan project tidak lagi hardcode di HTML — tapi disimpan di variabel dan array PHP. Tinggal tambah item ke array, halaman otomatis terupdate.

**"Apa tantangan terbesar?"**
> Memahami alur fungsi rekursif untuk render skill tree. Awalnya bingung karena fungsi memanggil dirinya sendiri, tapi setelah lihat base case-nya (saat item bukan array lagi), jadi paham polanya.

**"Apa bedanya `.html` dengan `.php`?"**
> File `.html` dikirim langsung ke browser. File `.php` diproses server dulu (kode PHP dieksekusi), hasil HTML-nya baru dikirim. Makanya kita bisa bikin konten dinamis.
