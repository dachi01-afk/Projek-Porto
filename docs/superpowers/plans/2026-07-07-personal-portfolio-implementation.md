# Personal Portfolio Website — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a personal portfolio website with dark modern theme using HTML + Tailwind CSS CDN.

**Architecture:** Single `index.html` file with Tailwind CSS via CDN for utility classes and custom `<style>` block for CSS animations/keyframes. Mobile-first responsive design.

**Tech Stack:** HTML5, Tailwind CSS 3.x (CDN), Custom CSS

## Global Constraints

- All code in a single `index.html` file
- Tailwind CSS via CDN (`https://cdn.tailwindcss.com`)
- Semantic HTML elements: `header`, `nav`, `section`, `footer`
- Mobile-first responsive design with media queries
- Minimum 1 CSS animation (fade-in on scroll, gradient background)
- CSS transition on interactive elements (hover)
- At least 2 Tailwind components (navbar, card, button, form)
- CSS Box Model applied (margin, padding, border, width, height)
- Dark modern minimalis theme with gradient accents

---
### Task 1: HTML Skeleton + Tailwind Setup + Navigation

**Files:**
- Create: `index.html`

- [ ] **Step 1: Write HTML skeleton with Tailwind CDN and navbar**

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portfolio</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Global styles */
    html { scroll-behavior: smooth; }
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body>
```

- [ ] **Step 2: Write navbar with glassmorphism effect**

```html
<header class="fixed top-0 left-0 w-full z-50 backdrop-blur-md bg-black/60 border-b border-white/10">
  <nav class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
    <a href="#" class="text-xl font-bold text-white">Portfolio.</a>

    <!-- Desktop menu -->
    <ul class="hidden md:flex space-x-8 text-gray-300">
      <li><a href="#about" class="hover:text-white transition-colors">About</a></li>
      <li><a href="#skills" class="hover:text-white transition-colors">Skills</a></li>
      <li><a href="#projects" class="hover:text-white transition-colors">Projects</a></li>
      <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
    </ul>

    <!-- Mobile hamburger -->
    <button id="menu-btn" class="md:hidden text-white focus:outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
  </nav>

  <!-- Mobile menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-black/90 border-t border-white/10">
    <ul class="flex flex-col items-center py-4 space-y-4 text-gray-300">
      <li><a href="#about" class="hover:text-white transition-colors">About</a></li>
      <li><a href="#skills" class="hover:text-white transition-colors">Skills</a></li>
      <li><a href="#projects" class="hover:text-white transition-colors">Projects</a></li>
      <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
    </ul>
  </div>
</header>
```

- [ ] **Step 3: Write the mobile menu toggle script**

```html
<script>
  const menuBtn = document.getElementById('menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');

  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });

  // Close mobile menu on link click
  mobileMenu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => mobileMenu.classList.add('hidden'));
  });
</script>
```

- [ ] **Step 4: Verify in browser**
  - Open `index.html` in browser
  - Navbar should be fixed at top with glass effect
  - Hamburger menu toggles on mobile viewport

---
### Task 2: Hero Section + About Section

**Files:**
- Modify: `index.html` (inside `<body>` after header)

- [ ] **Step 1: Write Hero section with animated gradient background**

```html
<!-- Hero -->
<section id="hero" class="min-h-screen flex items-center justify-center relative overflow-hidden bg-black">
  <!-- Animated gradient background -->
  <div class="absolute inset-0 bg-gradient-to-br from-purple-900/40 via-black to-blue-900/40 animate-gradient"></div>
  <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-purple-500/10 via-transparent to-transparent"></div>

  <div class="relative z-10 text-center px-6">
    <p class="text-purple-400 text-lg mb-4">Hello, I'm</p>
    <h1 class="text-5xl md:text-7xl font-bold mb-4 bg-gradient-to-r from-purple-400 via-pink-400 to-blue-400 bg-clip-text text-transparent">
      John Doe
    </h1>
    <p class="text-xl md:text-2xl text-gray-400 mb-8">Backend Developer & AI Enthusiast</p>
    <div class="flex gap-4 justify-center">
      <a href="#contact" class="px-8 py-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-full font-medium hover:scale-105 hover:shadow-lg hover:shadow-purple-500/25 transition-all duration-300">
        Contact Me
      </a>
      <a href="#projects" class="px-8 py-3 border border-gray-600 text-gray-300 rounded-full font-medium hover:border-purple-500 hover:text-purple-400 hover:scale-105 transition-all duration-300">
        View Projects
      </a>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Add the `animate-gradient` keyframes in `<style>`**

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

- [ ] **Step 3: Write About section**

```html
<!-- About -->
<section id="about" class="py-20 bg-black">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">About <span class="text-purple-400">Me</span></h2>
    <div class="flex flex-col md:flex-row items-center gap-10">
      <div class="w-48 h-48 md:w-64 md:h-64 rounded-full border-4 border-purple-500/30 overflow-hidden flex-shrink-0">
        <img src="https://api.dicebear.com/9.x/avataaars/svg?seed=john&backgroundColor=transparent" alt="avatar" class="w-full h-full object-cover" />
      </div>
      <div class="text-gray-400 text-lg leading-relaxed">
        <p class="mb-4">
          I'm a passionate <span class="text-purple-400">Backend Developer</span> and
          <span class="text-blue-400">AI Enthusiast</span> with a strong foundation in
          building scalable web applications and intelligent systems.
        </p>
        <p>
          Currently deepening my expertise at <span class="text-white">Maxy Academy</span>,
          I specialize in crafting robust APIs, optimizing databases, and exploring
          the intersection of AI and backend architecture. I love turning complex
          problems into elegant solutions.
        </p>
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 4: Verify in browser**
  - Hero section fullscreen with animated gradient background
  - About section with avatar and description below hero
  - Hover effects on buttons work (scale, shadow)

---
### Task 3: Skills Section

**Files:**
- Modify: `index.html`

- [ ] **Step 1: Write Skills section with grid layout**

```html
<!-- Skills -->
<section id="skills" class="py-20 bg-gray-950">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">My <span class="text-purple-400">Skills</span></h2>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

      <!-- Skill Card -->
      <div class="skill-card bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center hover:border-purple-500/50 hover:-translate-y-2 transition-all duration-300">
        <div class="text-4xl mb-3">🟢</div>
        <h3 class="text-white font-semibold mb-2">HTML & CSS</h3>
        <div class="w-full bg-gray-800 rounded-full h-2">
          <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full" style="width: 90%"></div>
        </div>
      </div>

      <div class="skill-card bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center hover:border-purple-500/50 hover:-translate-y-2 transition-all duration-300">
        <div class="text-4xl mb-3">💻</div>
        <h3 class="text-white font-semibold mb-2">JavaScript</h3>
        <div class="w-full bg-gray-800 rounded-full h-2">
          <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full" style="width: 80%"></div>
        </div>
      </div>

      <div class="skill-card bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center hover:border-purple-500/50 hover:-translate-y-2 transition-all duration-300">
        <div class="text-4xl mb-3">⚙️</div>
        <h3 class="text-white font-semibold mb-2">Node.js</h3>
        <div class="w-full bg-gray-800 rounded-full h-2">
          <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full" style="width: 85%"></div>
        </div>
      </div>

      <div class="skill-card bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center hover:border-purple-500/50 hover:-translate-y-2 transition-all duration-300">
        <div class="text-4xl mb-3">🐍</div>
        <h3 class="text-white font-semibold mb-2">Python</h3>
        <div class="w-full bg-gray-800 rounded-full h-2">
          <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full" style="width: 88%"></div>
        </div>
      </div>

      <div class="skill-card bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center hover:border-purple-500/50 hover:-translate-y-2 transition-all duration-300">
        <div class="text-4xl mb-3">🗄️</div>
        <h3 class="text-white font-semibold mb-2">SQL & MongoDB</h3>
        <div class="w-full bg-gray-800 rounded-full h-2">
          <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full" style="width: 82%"></div>
        </div>
      </div>

      <div class="skill-card bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center hover:border-purple-500/50 hover:-translate-y-2 transition-all duration-300">
        <div class="text-4xl mb-3">☁️</div>
        <h3 class="text-white font-semibold mb-2">Docker</h3>
        <div class="w-full bg-gray-800 rounded-full h-2">
          <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full" style="width: 75%"></div>
        </div>
      </div>

      <div class="skill-card bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center hover:border-purple-500/50 hover:-translate-y-2 transition-all duration-300">
        <div class="text-4xl mb-3">🤖</div>
        <h3 class="text-white font-semibold mb-2">AI / ML</h3>
        <div class="w-full bg-gray-800 rounded-full h-2">
          <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full" style="width: 70%"></div>
        </div>
      </div>

      <div class="skill-card bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center hover:border-purple-500/50 hover:-translate-y-2 transition-all duration-300">
        <div class="text-4xl mb-3">🐙</div>
        <h3 class="text-white font-semibold mb-2">Git & GitHub</h3>
        <div class="w-full bg-gray-800 rounded-full h-2">
          <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full" style="width: 90%"></div>
        </div>
      </div>

    </div>
  </div>
</section>
```

- [ ] **Step 2: Add fade-in animation for skill cards**

```css
.skill-card {
  opacity: 0;
  transform: translateY(30px);
  animation: fadeInUp 0.6s ease forwards;
}

.skill-card:nth-child(1) { animation-delay: 0.1s; }
.skill-card:nth-child(2) { animation-delay: 0.2s; }
.skill-card:nth-child(3) { animation-delay: 0.3s; }
.skill-card:nth-child(4) { animation-delay: 0.4s; }
.skill-card:nth-child(5) { animation-delay: 0.5s; }
.skill-card:nth-child(6) { animation-delay: 0.6s; }
.skill-card:nth-child(7) { animation-delay: 0.7s; }
.skill-card:nth-child(8) { animation-delay: 0.8s; }

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
```

- [ ] **Step 3: Verify in browser**
  - Skills section with 8 cards in a responsive grid
  - Cards animate (fadeInUp) on page load
  - Hover effect lifts card up with border color change

---
### Task 4: Projects Section + Contact Form

**Files:**
- Modify: `index.html`

- [ ] **Step 1: Write Projects section with project cards**

```html
<!-- Projects -->
<section id="projects" class="py-20 bg-black">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">Featured <span class="text-purple-400">Projects</span></h2>

    <div class="grid md:grid-cols-2 gap-8">

      <!-- Project Card 1 -->
      <div class="project-card bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden hover:border-purple-500/50 transition-all duration-300">
        <div class="h-48 bg-gradient-to-br from-purple-900/40 to-blue-900/40 flex items-center justify-center">
          <span class="text-purple-400 text-lg">Project Screenshot</span>
        </div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-white mb-2">E-Commerce API</h3>
          <p class="text-gray-400 mb-4">RESTful API for e-commerce platform with authentication, product management, and payment integration.</p>
          <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-3 py-1 text-xs bg-purple-500/20 text-purple-300 rounded-full">Node.js</span>
            <span class="px-3 py-1 text-xs bg-blue-500/20 text-blue-300 rounded-full">Express</span>
            <span class="px-3 py-1 text-xs bg-green-500/20 text-green-300 rounded-full">MongoDB</span>
            <span class="px-3 py-1 text-xs bg-yellow-500/20 text-yellow-300 rounded-full">JWT</span>
          </div>
          <div class="flex gap-4">
            <a href="#" class="text-purple-400 hover:text-purple-300 transition-colors text-sm">🔗 GitHub →</a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">🌐 Live Demo →</a>
          </div>
        </div>
      </div>

      <!-- Project Card 2 -->
      <div class="project-card bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden hover:border-purple-500/50 transition-all duration-300">
        <div class="h-48 bg-gradient-to-br from-blue-900/40 to-purple-900/40 flex items-center justify-center">
          <span class="text-blue-400 text-lg">Project Screenshot</span>
        </div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-white mb-2">AI Chatbot</h3>
          <p class="text-gray-400 mb-4">Intelligent chatbot powered by LLM with context-aware responses and conversation history tracking.</p>
          <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-3 py-1 text-xs bg-purple-500/20 text-purple-300 rounded-full">Python</span>
            <span class="px-3 py-1 text-xs bg-blue-500/20 text-blue-300 rounded-full">FastAPI</span>
            <span class="px-3 py-1 text-xs bg-green-500/20 text-green-300 rounded-full">LangChain</span>
            <span class="px-3 py-1 text-xs bg-red-500/20 text-red-300 rounded-full">OpenAI</span>
          </div>
          <div class="flex gap-4">
            <a href="#" class="text-purple-400 hover:text-purple-300 transition-colors text-sm">🔗 GitHub →</a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">🌐 Live Demo →</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
```

- [ ] **Step 2: Write Contact section with form**

```html
<!-- Contact -->
<section id="contact" class="py-20 bg-gray-950">
  <div class="max-w-4xl mx-auto px-6">
    <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">Get In <span class="text-purple-400">Touch</span></h2>

    <form class="max-w-xl mx-auto space-y-6">
      <div>
        <label for="name" class="block text-gray-400 mb-2">Name</label>
        <input type="text" id="name" required
          class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors"
          placeholder="Your Name" />
      </div>
      <div>
        <label for="email" class="block text-gray-400 mb-2">Email</label>
        <input type="email" id="email" required
          class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors"
          placeholder="your@email.com" />
      </div>
      <div>
        <label for="message" class="block text-gray-400 mb-2">Message</label>
        <textarea id="message" rows="5" required
          class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors resize-none"
          placeholder="Your message..."></textarea>
      </div>
      <button type="submit"
        class="w-full py-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-lg font-medium hover:scale-[1.02] hover:shadow-lg hover:shadow-purple-500/25 transition-all duration-300">
        Send Message
      </button>
    </form>
  </div>
</section>
```

- [ ] **Step 3: Verify in browser**
  - Projects section with 2 cards (dummy data), hover effects on cards
  - Contact form with all fields, input focus effect

---
### Task 5: Footer + Responsive Polish + Final Assembly

**Files:**
- Modify: `index.html`

- [ ] **Step 1: Write Footer section**

```html
<!-- Footer -->
<footer class="py-8 bg-black border-t border-gray-800">
  <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
    <p class="text-gray-500 text-sm">© 2026 John Doe. All rights reserved.</p>
    <div class="flex gap-6">
      <a href="#" class="text-gray-500 hover:text-purple-400 transition-colors text-lg">GitHub</a>
      <a href="#" class="text-gray-500 hover:text-purple-400 transition-colors text-lg">LinkedIn</a>
      <a href="#" class="text-gray-500 hover:text-purple-400 transition-colors text-lg">Instagram</a>
    </div>
  </div>
</footer>
```

- [ ] **Step 2: Add responsive media queries for fine-tuning**

```css
/* Custom scrollbar */
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { background: #000; }
::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
::-webkit-scrollbar-thumb:hover { background: #555; }

/* Responsive text adjustments */
@media (max-width: 640px) {
  .skill-card { padding: 1rem; }
}
```

- [ ] **Step 3: Add closing HTML tags and verify complete document structure**

Ensure closing tags for: `</body></html>` at end of file.

- [ ] **Step 4: Final verification**
  - Open `index.html` in browser (desktop + mobile DevTools)
  - All 5 sections render correctly
  - Navbar fixed at top, glassmorphism visible
  - Hero animated gradient background
  - About section with avatar
  - Skills grid with fade-in animation
  - Project cards with hover effects
  - Contact form with styled inputs
  - Footer with social links
  - Mobile: hamburger menu works, layout stacks properly
  - All hover transitions smooth
