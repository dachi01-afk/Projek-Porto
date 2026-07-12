# Dokumentasi JavaScript Portfolio - Task 2

## Daftar Isi
1. [Setup & Struktur File](#1-setup--struktur-file)
2. [Variabel, Data Type & Operator](#2-variabel-data-type--operator)
3. [Conditional (Greeting Dinamis)](#3-conditional-greeting-dinamis)
4. [Looping (Render Skill Cards)](#4-looping-render-skill-cards)
5. [jQuery DOM Manipulation](#5-jquery-dom-manipulation)
6. [jQuery Event Handling](#6-jquery-event-handling)
7. [Form Validation](#7-form-validation)
8. [Chart.js - Reporting Charts](#8-chartjs---reporting-charts)
9. [Chart.js - Dashboard & Advanced](#9-chartjs---dashboard--advanced)
10. [Debugging](#10-debugging)

---

## 1. Setup & Struktur File

**File yang ditambahkan/dimodifikasi:**

| File | Fungsi |
|------|--------|
| `script.js` | Logic JavaScript untuk halaman utama (index.html) |
| `dashboard.js` | Logic JavaScript khusus halaman dashboard |
| `dashboard.html` | Halaman terpisah untuk dashboard charts |
| `style.css` | CSS dipisah dari HTML |
| `index.html` | Ditambahkan link ke CSS, JS, jQuery, Chart.js, section baru |

**Cara menghubungkan:**
```html
<!-- Di <head> index.html & dashboard.html -->
<link rel="stylesheet" href="style.css" />
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Sebelum </body> index.html -->
<script src="script.js"></script>

<!-- Sebelum </body> dashboard.html -->
<script src="dashboard.js"></script>
```

**Pertanyaan mentor:** "Kenapa pakai jQuery?"
**Jawaban:** jQuery memudahkan DOM manipulation, event handling, dan animasi dengan syntax yang lebih ringkas. Contoh: `$('#greeting').text('Hello')` vs `document.getElementById('greeting').textContent = 'Hello'`.

**Pertanyaan mentor:** "Kenapa Chart.js tetap pakai vanilla JS?"
**Jawaban:** Chart.js berinteraksi langsung dengan canvas element. `document.getElementById()` sudah cukup dan lebih natural untuk library Chart.js.

---

## 2. Variabel, Data Type & Operator

```javascript
// Variabel
const portfolioOwner = 'Jimi Firgo Dakhi';  // string
let currentYear = 2026;                      // number
const isBootcampComplete = false;            // boolean

// Array (object)
const skillsData = [
  { name: 'PHP / Laravel', level: 92, icon: '🐘' },
  { name: 'HTML & CSS', level: 85, icon: '🟢' }
];

// Operator
const totalSkills = skillsData.length;       // arithmetic
const isSkilled = level >= 80;               // comparison
const isMaster = level >= 90 && level <= 100; // logical
```

**Tipe data yang digunakan:**
- `string` — teks (nama, greeting)
- `number` — angka (level, persentase)
- `boolean` — true/false
- `object` — array, object literal
- `undefined` / `null` — (saat element tidak ditemukan)

**Pertanyaan mentor:** "Bedanya `const`, `let`, `var`?"
**Jawaban:** `const` — tidak bisa reassign. `let` — bisa reassign, scope block. `var` — function scope (hindari).

---

## 3. Conditional (Greeting Dinamis)

```javascript
function setGreeting() {
  const hour = new Date().getHours();
  let greeting;

  if (hour >= 5 && hour < 12) {
    greeting = 'Good Morning';
  } else if (hour >= 12 && hour < 17) {
    greeting = 'Good Afternoon';
  } else if (hour >= 17 && hour < 21) {
    greeting = 'Good Evening';
  } else {
    greeting = 'Good Night';
  }

  // jQuery: mengubah text element
  $('#greeting').text(greeting);
}
```

**Alur:** Ambil jam saat ini → tentukan salam → tampilkan ke elemen `<span id="greeting">`.

**Bisa dikembangkan:** Tambah `switch` case untuk bahasa (Indonesia/English).

---

## 4. Looping (Render Skill Cards)

### $.each — render data ke HTML

```javascript
$.each(skillsData, function (index, skill) {
  const $card = $('<div>')
    .addClass('skill-card ...')
    .html(`...${skill.icon}...${skill.name}...`);

  $container.append($card);
});
```

**Kenapa `$.each`?** jQuery version of `forEach`, syntax lebih ringkas untuk iterasi array.

**Alternatif** (kalau ditanya mentor):
- `skillsData.forEach()` — vanilla JS ES6
- `for (let i = 0; i < skillsData.length; i++)` — klasik
- `for (let skill of skillsData)` — ES6

---

## 5. jQuery DOM Manipulation

### a. Mengubah text element
```javascript
$('#greeting').text(greeting);              // Set text (jQuery)
```

### b. Menambah/menghapus class
```javascript
$mobileMenu.toggleClass('hidden');          // Toggle class
$nameInput.addClass('form-error');          // Add class
$nameError.removeClass('visible');          // Remove class
```

### c. Membuat element baru
```javascript
const $card = $('<div>')
  .addClass('skill-card ...')
  .html(`<h3>${skill.name}</h3>`);
$container.append($card);
```

### d. Mengubah style
```javascript
$('.skill-bar').css('width', target + '%'); // Inline style (jQuery)
```

### e. Selector jQuery
```javascript
$('#menu-btn')       // ID selector
$('.skill-grid')     // Class selector
$('div')             // Tag selector
$('#mobile-menu').find('a')  // Descendant selector
```

**Pertanyaan mentor:** "Apa bedanya jQuery vs vanilla JS?"
**Jawaban:** jQuery lebih ringkas. `$('#el').text('x')` vs `document.getElementById('el').textContent = 'x'`. Tapi vanilla JS lebih cepat dan tidak perlu library tambahan.

---

## 6. jQuery Event Handling

### Event yang digunakan:

| Event | Elemen | Fungsi |
|-------|--------|--------|
| `click` | Menu button | Toggle mobile menu |
| `click` | Nav link (mobile) | Tutup menu setelah klik |
| `click` | Refresh button (dashboard.html) | Update chart data |
| `submit` | Contact form | Validasi & kirim |
| `change` | Input fields | Validasi real-time |

### Contoh:
```javascript
// jQuery event binding
$('#menu-btn').on('click', function () {
  $mobileMenu.toggleClass('hidden');
});

$form.on('submit', function (e) {
  e.preventDefault();  // Cegah reload
  // validasi...
});
```

**Pertanyaan mentor:** "Kenapa pakai `e.preventDefault()`?"
**Jawaban:** Agar form tidak reload halaman saat submit, sehingga kita bisa validasi dulu dengan JS.

---

## 7. Form Validation

**Fitur:**
- Validasi real-time (saat user selesai isi field)
- Cek panjang nama (min 3 karakter)
- Cek format email (regex)
- Cek pesan tidak kosong
- Style error (border merah + pesan error)
- Toast notification sukses/gagal

### Regex email:
```javascript
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
```

### Validasi dengan jQuery:
```javascript
if ($nameInput.val().trim().length < 3) {
  $nameInput.addClass('form-error');
  $nameError.addClass('visible');
  isValid = false;
} else {
  $nameInput.removeClass('form-error');
  $nameError.removeClass('visible');
}
```

**Pertanyaan mentor:** "Apa itu regex?"
**Jawaban:** Pola untuk mencocokkan teks. `^[^\s@]+@[^\s@]+\.[^\s@]+$` artinya: harus ada karakter sebelum `@`, setelah `@`, dan setelah `.`.

---

## 8. Chart.js - Reporting Charts

**Integrasi:** CDN di head (`chart.js`). Chart.js tetap pakai vanilla JS untuk akses canvas.

### 3 Chart Reporting:

| Chart | Data | Tipe |
|-------|------|------|
| **Bar Chart** | Skill level per technology | `type: 'bar'` |
| **Line Chart** | Progress belajar per minggu | `type: 'line'` |
| **Pie Chart** | Distribusi tech stack | `type: 'pie'` |

### Scriptable Options (Bar Chart):
```javascript
backgroundColor: skillsData.map(s => {
  if (s.level >= 90) return 'rgba(168, 85, 247, 0.8)';
  if (s.level >= 80) return 'rgba(59, 130, 246, 0.8)';
  if (s.level >= 70) return 'rgba(34, 197, 94, 0.8)';
  return 'rgba(234, 179, 8, 0.8)';
})
```

Warna bar berubah **dinamis berdasarkan nilai** — ini adalah **Scriptable Option**.

---

## 9. Chart.js - Dashboard & Advanced

Dashboard berada di halaman terpisah (`dashboard.html`) dengan file JS khusus (`dashboard.js`).

### 2 Chart Dashboard:

| Chart | Data | Tipe |
|-------|------|------|
| **Stacked Bar** | Effort per project (Backend, Frontend, Database) | `type: 'bar'` + stacked |
| **Polar Area** | Total effort per project | `type: 'polarArea'` |

### Advanced Features:

#### 1. Scriptable Options
Warna pada Stacked Bar dan Polar Area menggunakan array warna yang sudah ditentukan. Di Polar Area, setiap segmen memiliki warna berbeda yang merepresentasikan project berbeda.

#### 2. Animation
Chart.js animation aktif secara default. Setiap kali tombol Refresh diklik, chart di-*update* ulang sehingga animasi berjalan kembali.

#### 3. Programmatic Event Trigger (jQuery)
```javascript
$('#refreshDashboard').on('click', function () {
  // Update data stacked bar dengan angka random
  stackedBarChart.data.datasets.forEach(dataset => {
    dataset.data = dataset.data.map(() => Math.floor(20 + Math.random() * 50));
  });
  stackedBarChart.update();

  // Update data polar area dengan angka random
  polarChart.data.datasets[0].data = polarChart.data.datasets[0].data.map(() => 50 + Math.random() * 100);
  polarChart.update();
});
```
Saat tombol "Refresh Data" diklik → kedua chart diperbarui dengan data acak + animasi ulang.

---

## 10. Debugging

### console.log yang digunakan:
```javascript
console.log('Portfolio Owner:', portfolioOwner);
console.log('Skills count:', skillsData.length);
console.log('Browser:', navigator.userAgent);
console.log('Page loaded at:', new Date().toISOString());
console.log('%c Portfolio JS Loaded (jQuery) ', 'background: #a855f7; color: white;');
```

### Cara menggunakan DevTools:
1. Buka browser (Chrome/Edge)
2. Klik kanan → Inspect / F12
3. Tab **Console** → lihat output log
4. Tab **Elements** → lihat DOM structure
5. Tab **Sources** → breakpoint debugging
6. Tab **Network** → lihat request/response

**Pertanyaan mentor:** "Apa bedanya `console.log`, `console.error`, `console.table`?"
**Jawaban:** `log` — info umum. `error` — pesan error (merah). `table` — tampilkan array sebagai tabel.

---

## Ringkasan untuk Presentasi ke Mentor

| Requirements | Status | Lokasi di Code |
|-------------|--------|----------------|
| File JS eksternal | ✅ | `script.js` (index), `dashboard.js` (dashboard) |
| Variabel & data type | ✅ | Baris 10-45 (`const`, `let`, string, number, boolean, array, object) |
| Operator | ✅ | Perbandingan (`>=`), logika (`&&`), arithmetic (`length`) |
| Conditional (if/else) | ✅ | `setGreeting()` — percabangan 4 kondisi waktu |
| Looping (`$.each`) | ✅ | `renderSkillBars()` — loop array skills |
| jQuery DOM Manipulation | ✅ | `$.text()`, `$.html()`, `$.addClass()`, `$.toggleClass()`, `$.append()`, `$.css()` |
| jQuery Event Handling | ✅ | `$.on('click')`, `$.on('submit')`, `$.on('change')` |
| Debugging console.log | ✅ | 6 titik log di berbagai fungsi |
| Chart.js (3 chart reporting) | ✅ | Bar, Line, Pie — `initCharts()` |
| Chart.js (dashboard) | ✅ | Stacked Bar, Polar Area — `dashboard.js` |
| Scriptable Options | ✅ | Warna bar dinamis berdasarkan nilai |
| Dashboard halaman terpisah | ✅ | `dashboard.html` + `dashboard.js` |
| Animation Chart | ✅ | Default + trigger ulang via `update()` |
| Programmatic Event Trigger | ✅ | Tombol Refresh → update data chart |
| CSS file terpisah | ✅ | `style.css` |
| jQuery Integration | ✅ | CDN di `<head>`, digunakan untuk DOM & Events |
