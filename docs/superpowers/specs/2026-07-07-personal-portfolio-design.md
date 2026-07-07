# Personal Portfolio Website — Design Spec

## Overview
Personal Portfolio Website for Maxy Academy bootcamp project (Backend + AI track). Built with semantic HTML, CSS, and Tailwind CSS via CDN. Dark modern minimalis theme.

## Sections

### 1. Header / Navigation
- Fixed navbar with glassmorphism background (backdrop-blur)
- Logo/nama di kiri, menu links di kanan: About, Skills, Projects, Contact
- Mobile: hamburger menu dengan toggle
- Smooth scroll navigation

### 2. Hero Section
- Full viewport height (`min-h-screen`)
- Nama besar dengan gradient text (tailwind gradient)
- Subtitle: "Backend Developer & AI Enthusiast"
- CTA buttons: "Contact Me" dan "View Projects" dengan hover transition
- Background: animated subtle gradient (CSS animation)

### 3. About Me
- Dua kolom layout (flex/grid): avatar bulat + deskripsi singkat
- Dark card dengan border subtle
- Dummy data untuk nama dan deskripsi

### 4. Skills
- Grid layout dengan skill cards
- Tiap card: icon + nama skill + level/progress
- Animasi fade-in saat di-scroll
- Kategori: Programming Languages, Backend, Tools & Platforms, AI/ML

### 5. Projects
- Card-style project cards dalam grid
- Tiap card: gambar placeholder, judul, deskripsi, tech stack badges, link GitHub
- Hover effect: scale + shadow transition
- Link GitHub disediakan oleh user nanti

### 6. Contact
- Form: Nama, Email, Pesan (textarea)
- Styling Tailwind dengan dark theme input
- Submit button dengan hover transition
- Client-side form validation (required)

### 7. Footer
- Nama, copyright
- Social media links: GitHub, LinkedIn, Instagram (icon)

## Technical Details

### CSS Library
- **Tailwind CSS** via CDN (`https://cdn.tailwindcss.com`)
- Minimal 2 komponen: Navbar, Card, Button, Form

### Layout
- Flexbox & Grid dari Tailwind
- CSS Box Model (margin, padding, border, width, height)

### Animation & Transition
- 1 CSS animation: gradient background di hero + fade-in on scroll
- CSS transition: hover pada button, link, card

### Responsive Design
- Mobile-first approach
- Breakpoints: sm (640px), md (768px), lg (1024px)
- Mobile: stacked layout, hamburger nav
- Desktop: horizontal nav, multi-column layout

### HTML Structure
```html
<header>
  <nav>
    <a>Logo</a>
    <ul>Menu items</ul>
  </nav>
</header>

<section id="hero">
<section id="about">
<section id="skills">
<section id="projects">
<section id="contact">

<footer>
```

### File Structure
```
projek-portofolio/
├── index.html
└── docs/superpowers/specs/2026-07-07-personal-portfolio-design.md
```

All styles via Tailwind utility classes + inline `<style>` for custom CSS (animations, keyframes).
