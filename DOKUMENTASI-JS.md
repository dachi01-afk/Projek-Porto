# Dokumentasi JavaScript Portfolio - Task 2

## Daftar Isi
1. [Setup & Struktur File](#1-setup--struktur-file)
2. [Variabel, Data Type & Operator](#2-variabel-data-type--operator)
3. [Conditional (Greeting Dinamis)](#3-conditional-greeting-dinamis)
4. [Looping (Render Skill Cards)](#4-looping-render-skill-cards)
5. [DOM Manipulation](#5-dom-manipulation)
6. [Event Handling](#6-event-handling)
7. [Form Validation](#7-form-validation)
8. [Chart.js - Reporting Charts](#8-chartjs---reporting-charts)
9. [Chart.js - Dashboard & Advanced](#9-chartjs---dashboard--advanced)
10. [Debugging](#10-debugging)

---

## 1. Setup & Struktur File

**File yang ditambahkan/dimodifikasi:**

| File | Fungsi |
|------|--------|
| `script.js` | Semua logic JavaScript |
| `style.css` | CSS dipisah dari HTML |
| `index.html` | Ditambahkan link ke CSS, JS, Chart.js, section baru |

**Cara menghubungkan:**
```html
<!-- Di <head> -->
<link rel="stylesheet" href="style.css" />
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Sebelum </body> -->
<script src="script.js"></script>
```

**Pertanyaan mentor:** "Kenapa script.js ditaruh di akhir?"
**Jawaban:** Agar DOM sudah selesai dirender sebelum JS dijalankan. Alternatif: pakai `defer` di `<head>`.

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

  document.getElementById('greeting').textContent = greeting;
}
```

**Alur:** Ambil jam saat ini → tentukan salam → tampilkan ke elemen `<span id="greeting">`.

**Bisa dikembangkan:** Tambah `switch` case untuk bahasa (Indonesia/English).

---

## 4. Looping (Render Skill Cards)

### forEach — render data ke HTML

```javascript
skillsData.forEach((skill, index) => {
  const card = document.createElement('div');
  card.className = 'skill-card ...';
  card.innerHTML = `...${skill.icon}...${skill.name}...`;
  container.appendChild(card);
});
```

**Kenapa forEach?** Lebih modern dan readable dibanding `for` loop biasa. Cocok untuk array.

**Alternatif** (kalau ditanya mentor):
- `for (let i = 0; i < skillsData.length; i++)` — klasik
- `for (let skill of skillsData)` — ES6
- `skillsData.map()` — kalau perlu return array baru

---

## 5. DOM Manipulation

### a. Mengubah text element
```javascript
greetingEl.textContent = greeting;       // Set text
```

### b. Menambah/menghapus class
```javascript
mobileMenu.classList.toggle('hidden');   // Toggle class
nameInput.classList.add('form-error');   // Add class
nameError.classList.remove('visible');   // Remove class
```

### c. Membuat element baru
```javascript
const card = document.createElement('div');
card.innerHTML = `<h3>${skill.name}</h3>`;
container.appendChild(card);
```

### d. Mengubah style
```javascript
bar.style.width = target + '%';          // Inline style
```

**Pertanyaan mentor:** "Apa bedanya `textContent` vs `innerHTML`?"
**Jawaban:** `textContent` — hanya teks, aman dari XSS. `innerHTML` — parse HTML, bisa disisipi tag.

---

## 6. Event Handling

### Event Listener yang digunakan:

| Event | Elemen | Fungsi |
|-------|--------|--------|
| `click` | Menu button | Toggle mobile menu |
| `click` | Nav link (mobile) | Tutup menu setelah klik |
| `click` | Refresh button | Update chart data |
| `submit` | Contact form | Validasi & kirim |
| `change` | Input fields | Validasi real-time |

### Contoh:
```javascript
menuBtn.addEventListener('click', () => {
  mobileMenu.classList.toggle('hidden');
});

form.addEventListener('submit', (e) => {
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

**Pertanyaan mentor:** "Apa itu regex?"
**Jawaban:** Pola untuk mencocokkan teks. `^[^\s@]+@[^\s@]+\.[^\s@]+$` artinya: harus ada karakter sebelum `@`, setelah `@`, dan setelah `.`.

---

## 8. Chart.js - Reporting Charts

**Integrasi:** CDN di head (`chart.js`).

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

### 2 Chart Dashboard:

| Chart | Data | Fitur |
|-------|------|-------|
| **Stacked Bar** | Effort per project (Backend, Frontend, Database) | Stacked |
| **Scatter** | Commit activity (random points) | Linear axis |

### Advanced Features:

#### 1. Scriptable Options
Warna bar chart ditentukan oleh value data (≥90 = purple, ≥80 = blue, dst).

#### 2. Animation
Chart.js animation aktif secara default. Bisa dikustom:
```javascript
animation: {
  duration: 1000,
  easing: 'easeInOutQuart'
}
```

#### 3. Programmatic Event Trigger
```javascript
refreshBtn.addEventListener('click', () => {
  // Update data chart
  stackedBarChart.data.datasets[0].data = newData;
  stackedBarChart.update();
  // Trigger animation lagi
});
```
Saat tombol "Refresh Data" diklik → chart diperbarui dengan data random + animasi ulang.

---

## 10. Debugging

### console.log yang digunakan:
```javascript
console.log('Portfolio Owner:', portfolioOwner);
console.log('Skills count:', skillsData.length);
console.log('Browser:', navigator.userAgent);
console.log('Page loaded at:', new Date().toISOString());
console.log('%c Styled log ', 'background: purple; color: white;');
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
| File JS eksternal | ✅ | `script.js` |
| Variabel & data type | ✅ | Baris 10-45 (`const`, `let`, string, number, boolean, array, object) |
| Operator | ✅ | Perbandingan (`>=`), logika (`&&`), arithmetic (`length`) |
| Conditional (if/else) | ✅ | `setGreeting()` — percabangan 4 kondisi waktu |
| Looping (forEach) | ✅ | `renderSkillBars()` — loop array skills |
| DOM Manipulation | ✅ | `createElement`, `textContent`, `classList`, `innerHTML`, `style` |
| Event Listener | ✅ (5) | `click` (menu, refresh), `submit` (form), `change` (input) |
| Debugging console.log | ✅ | 6 titik log di berbagai fungsi |
| Chart.js (3 chart reporting) | ✅ | Bar, Line, Pie — `initCharts()` |
| Chart.js (dashboard) | ✅ | Stacked Bar, Scatter — `initCharts()` |
| Scriptable Options | ✅ | Warna bar dinamis berdasarkan nilai |
| Animation Chart | ✅ | Default + trigger ulang via `update()` |
| Programmatic Event Trigger | ✅ | Tombol Refresh → update data chart |
| CSS file terpisah | ✅ | `style.css` |
