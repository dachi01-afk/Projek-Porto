# Dokumentasi Portfolio Website

## Daftar Isi
1. [HTML Structure & Semantic Elements](#1-html-structure--semantic-elements)
2. [CSS Box Model](#2-css-box-model)
3. [Flexbox & Layout](#3-flexbox--layout)
4. [CSS Animation & Transition](#4-css-animation--transition)
5. [Responsive Design (Mobile-First)](#5-responsive-design-mobile-first)
6. [Tailwind CSS Integration](#6-tailwind-css-integration)

---

## 1. HTML Structure & Semantic Elements

**Apa itu Semantic HTML?**
Elemen HTML yang memiliki *makna* tentang konten di dalamnya, bukan hanya sebagai wadah. Ini penting untuk SEO dan accessibility.

**Yang digunakan di project:**

| Elemen | Lokasi (baris) | Fungsinya |
|--------|---------------|-----------|
| `<header>` | 76 | Container untuk navigasi |
| `<nav>` | 77 | Navigasi utama website |
| `<section>` | 108, 130, 156, 232, 285 | Membagi halaman per bagian (hero, about, skills, dll) |
| `<footer>` | 317 | Informasi copyright di bagian bawah |
| `<h1>` | 114 | Judul utama (nama saya) |
| `<h2>` | 132, 158, 234, 287 | Sub-judul tiap section |
| `<h3>` | 165, 244, 265 | Nama skill / project |
| `<p>` | 117, 138-148 | Paragraf teks |
| `<a>` | 80-84, 119-124, 321-323 | Link navigasi dan sosial media |
| `<img>` | 135 | Foto/avatar profil |
| `<ul>` / `<li>` | 80-84, 97-103 | List menu navigasi |
| `<form>` / `<input>` / `<textarea>` | 289-311 | Form kontak |

**Pertanyaan mentor:** "Apa bedanya `<div>` sama `<section>`?"
**Jawaban:** `<section>` punya makna — dia menandai satu bagian tematik. `<div>` cuma wadah tanpa makna. Screen reader bisa navigasi ke section. SEO juga lebih baik.

---

## 2. CSS Box Model

**Apa itu Box Model?**
Setiap elemen HTML adalah sebuah kotak yang terdiri dari: `content` → `padding` → `border` → `margin`.

**Ilustrasi:**
```
+--------------------------+
|   margin (luar)          |
|   +-------------------+  |
|   | border            |  |
|   |  +-------------+  |  |
|   |  | padding     |  |  |
|   |  |  +-------+  |  |  |
|   |  |  |content|  |  |  |
|   |  |  +-------+  |  |  |
|   |  +-------------+  |  |
|   +-------------------+  |
+--------------------------+
```

**Yang digunakan di project:**

| Properti | Contoh Lokasi | Penjelasan |
|----------|--------------|------------|
| `padding` | `.px-6, .py-20, .p-6` (Tailwind) | Ruang di dalam elemen |
| `margin` | `.mx-auto, .mb-4, .gap-6` (Tailwind) | Ruang di luar elemen |
| `border` | `border border-gray-800` (baris 163) | Garis tepi kartu skill |
| `width` / `height` | `w-48 h-48` (baris 134) | Ukuran avatar |

**Pertanyaan mentor:** "Apa bedanya padding dan margin?"
**Jawaban:** Padding = ruang di *dalam* border (antara border dan konten). Margin = ruang di *luar* border (antara elemen dan elemen lain).

---

## 3. Flexbox & Layout

**Apa itu Flexbox?**
Metode layout CSS untuk mengatur elemen dalam satu baris atau kolom. Sangat membantu untuk membuat layout responsif.

**Yang digunakan di project:**

```css
/* Navbar - elemen sejajar horizontal */
.navbar { display: flex; align-items: center; justify-content: space-between; }

/* About - gambar dan teks (stack di mobile, sejajar di desktop) */
.about-layout { display: flex; flex-direction: column; }
@media (min-width: 768px) { .about-layout { flex-direction: row; } }

/* Skill cards - grid 2 kolom mobile, 4 kolom desktop */
.skill-grid { display: grid; grid-template-columns: repeat(2, 1fr); }
@media (min-width: 768px) { .skill-grid { grid-template-columns: repeat(3, 1fr); } }
@media (min-width: 1024px) { .skill-grid { grid-template-columns: repeat(4, 1fr); } }

/* Project cards - grid 1 kolom mobile, 2 kolom desktop */
.project-grid { display: grid; }
@media (min-width: 768px) { .project-grid { grid-template-columns: repeat(2, 1fr); } }
```

**Pertanyaan mentor:** "Kenapa pake Flexbox/Grid?"
**Jawaban:** Flexibox dan Grid memudahkan layout responsif tanpa pakai float atau position manual. Grid untuk layout 2 dimensi (baris + kolom), Flexbox untuk 1 dimensi (baris ATAU kolom).

---

## 4. CSS Animation & Transition

### Animation
Animasi berjalan otomatis tanpa interaksi user.

**1. Animated Gradient Background** (baris 13-21)
```css
@keyframes gradient {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.animate-gradient {
  background-size: 200% 200%;
  animation: gradient 8s ease infinite;
}
```
**Efek:** Background Hero bergerak perlahan (gradient berubah posisi).

**2. Fade In Up Skill Cards** (baris 23-43)
```css
.skill-card {
  opacity: 0;
  transform: translateY(30px);
  animation: fadeInUp 0.6s ease forwards;
}
@keyframes fadeInUp {
  to { opacity: 1; transform: translateY(0); }
}
```
**Efek:** Kartu skill muncul satu per satu dari bawah dengan delay berbeda (0.1s - 0.8s).

### Transition
Transisi terjadi saat user berinteraksi (hover).

```css
/* Navbar link */
.hover:text-white transition-colors  /* warna berubah halus saat hover */

/* Button Contact Me */
.hover:scale-105 hover:shadow-lg hover:shadow-purple-500/25 transition-all duration-300
/* Membesar sedikit + glow shadow saat hover */

/* Skill card */
.hover:border-purple-500/50 hover:-translate-y-2 transition-all duration-300
/* Border berubah warna dan card terangkat saat hover */
```

**Pertanyaan mentor:** "Bedanya animation sama transition?"
**Jawaban:** Animation berjalan otomatis (tanpa trigger). Transition terjadi saat ada perubahan state (hover, focus, dll).

---

## 5. Responsive Design (Mobile-First)

**Apa itu Mobile-First?**
Desain dimulai dari ukuran layar terkecil (HP), lalu ditambahkan breakpoint untuk layar lebih besar.

**Breakpoint yang digunakan:**

| Tailwind Class | Ukuran Layar | Target Device |
|---------------|--------------|---------------|
| Default (tanpa prefix) | < 768px | Mobile |
| `md:` | ≥ 768px | Tablet |
| `lg:` | ≥ 1024px | Desktop |

**Contoh penerapan:**

```html
<!-- Desktop nav: hidden di mobile, flex di tablet ke atas -->
<ul class="hidden md:flex">

<!-- Hamburger button: tampil di mobile, hidden di tablet ke atas -->
<button class="md:hidden">

<!-- Skill grid: 2 kolom mobile, 3 tablet, 4 desktop -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
```

**CSS Fallback** (baris 52-72):
Saya juga tambahkan CSS manual sebagai cadangan jika Tailwind CDN tidak jalan. Ini memastikan layout tetap rapi di device manapun.

**Pertanyaan mentor:** "Kenapa mobile-first?"
**Jawaban:** Lebih mudah scale-up daripada scale-down. Performa lebih baik karena mobile jadi baseline, dan mayoritas traffic sekarang dari mobile.

---

## 6. Tailwind CSS Integration

**Apa itu Tailwind CSS?**
Framework CSS utility-based. Kita tidak menulis CSS custom, tapi menggabungkan class-class kecil di HTML.

**Cara install di project ini (CDN):**
```html
<script src="https://cdn.tailwindcss.com"></script>
```

**2 Komponen Tailwind yang digunakan:**

### 1. Navbar (Tailwind Component)
```html
<header class="fixed top-0 left-0 w-full z-50 backdrop-blur-md bg-black/60 border-b border-white/10">
```
- `fixed` → navbar tetap di atas saat di-scroll
- `backdrop-blur-md` → efek glassmorphism (transparan blur)
- `z-50` → selalu di depan konten lain

### 2. Card (Tailwind Component)
```html
<div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center hover:border-purple-500/50">
```
- `rounded-xl` → sudut membulat
- `border` → garis tepi
- `p-6` → padding
- `hover:border-purple-500/50` → border berubah warna saat hover

### 3. Button
```html
<a class="px-8 py-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-full">
```
- `px-8 py-3` → padding horizontal & vertikal
- `rounded-full` → tombol full rounded (pill shape)
- `bg-gradient-to-r` → background gradient

### 4. Form
```html
<input class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white focus:outline-none focus:border-purple-500 transition-colors">
```
- `focus:border-purple-500` → border berubah saat input aktif
- `placeholder-gray-600` → warna placeholder abu-abu

**Pertanyaan mentor:** "Kelebihan Tailwind dibanding CSS biasa?"
**Jawaban:** Development lebih cepat (tidak perlu ganti file CSS), ukuran file lebih kecil (tidak ada CSS yang tidak terpakai), konsistensi desain terjaga.

---

## Ringkasan untuk Presentasi ke Mentor

| Requirements | Status | Lokasi di Code |
|-------------|--------|----------------|
| Header / Navigation | ✅ | Baris 76-105 |
| About Me | ✅ | Baris 130-153 |
| Skills | ✅ | Baris 156-229 |
| Projects / Experience | ✅ | Baris 232-282 |
| Contact Section | ✅ | Baris 285-314 |
| Semantic HTML | ✅ | header, nav, section, footer, h1-h3, form |
| CSS Box Model | ✅ | Padding, margin, border, width, height |
| Flexbox / Grid | ✅ | Navbar, About, Skills Grid, Projects Grid |
| CSS Animation | ✅ (2) | Gradient hero (baris 13-21), FadeInUp skill cards (baris 23-43) |
| CSS Transition | ✅ | Hover button, card, link, form input |
| Mobile-First Responsive | ✅ | Breakpoints md:, lg:, CSS fallback (baris 52-72) |
| CSS Library (Tailwind) | ✅ | CDN (baris 8), min 2 komponen: navbar, card, button, form |
