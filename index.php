<?php
$nama = "Jimi Firgo Dakhi";
$role = "Backend Developer";
$bio_1 = "I'm a <span class='text-purple-400'>Backend Developer</span> passionate about building reliable, scalable, and maintainable web applications.";
$bio_2 = "I recently completed my Informatics Engineering degree and gained practical experience through internships as a <span class='text-blue-400'>Junior Web Developer</span> and <span class='text-blue-400'>QC Intern</span>.";
$bio_3 = "I enjoy designing backend systems that not only function well but are also maintainable and aligned with real business needs.";
$bio_4 = "I believe that great software is built through continuous learning, collaboration, and a strong understanding of both technology and business requirements.";
$github = "https://github.com/dachi01-afk";
$linkedin = "https://www.linkedin.com/in/jimy-firgo-dakh";
$instagram = "https://www.instagram.com/jimi_firgo";
$email = "jimi@example.com";
$foto = "https://lh3.googleusercontent.com/d/1ySRzEfi0siuiqdYIxxQvBQd8anATY-1x";
$foto_fallback = "https://drive.google.com/thumbnail?id=1ySRzEfi0siuiqdYIxxQvBQd8anATY-1x&sz=w800";
$open_to_work = true;

$skills = [
  ["name" => "PHP / Laravel", "level" => 92, "icon" => "🐘"],
  ["name" => "HTML & CSS",    "level" => 85, "icon" => "🟢"],
  ["name" => "Tailwind CSS",  "level" => 82, "icon" => "🎨"],
  ["name" => "JavaScript",    "level" => 75, "icon" => "💻"],
  ["name" => "MySQL",         "level" => 85, "icon" => "🗄️"],
  ["name" => "Blade",         "level" => 88, "icon" => "📄"],
  ["name" => "REST API",      "level" => 80, "icon" => "🔗"],
  ["name" => "Git & GitHub",  "level" => 90, "icon" => "🐙"]
];

$projects = [
  [
    "title" => "LMS Royal Prima",
    "desc" => "Learning Management System untuk mengelola pembelajaran, materi, dan evaluasi siswa secara terintegrasi.",
    "tech" => ["Laravel", "PHP", "Blade", "MySQL"],
    "link" => "https://github.com/aurelioo29/lms-royal-prima",
    "icon" => "📚",
    "category" => "Fullstack"
  ],
  [
    "title" => "Overtime Request System",
    "desc" => "Sistem pengajuan lembur digital dengan approval workflow multi-level, perhitungan otomatis, dan pelaporan.",
    "tech" => ["Laravel", "PHP", "Tailwind", "MySQL"],
    "link" => "https://github.com/dachi01-afk/Overtime-Request-System",
    "icon" => "🕒",
    "category" => "Backend"
  ],
  [
    "title" => "SimpleAttendance",
    "desc" => "Sistem absensi berbasis web dengan fitur check-in/check-out, riwayat kehadiran, dan dashboard admin.",
    "tech" => ["Laravel", "PHP", "Tailwind", "MySQL"],
    "link" => "https://github.com/dachi01-afk/SimpleAttendance",
    "icon" => "📋",
    "category" => "Backend"
  ],
  [
    "title" => "Antrian-Ku",
    "desc" => "Sistem manajemen antrian digital untuk instansi pelayanan publik dengan Multi Channel Single Phase.",
    "tech" => ["Laravel", "PHP", "Blade", "jQuery"],
    "link" => "https://github.com/itsmeFer/Antrian-Ku",
    "icon" => "🔢",
    "category" => "Frontend"
  ]
];

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

function getCategoryLabel($category) {
  switch ($category) {
    case "Backend":
      return '<span class="px-3 py-1 text-xs bg-purple-500/20 text-purple-300 rounded-full">Backend</span>';
    case "Frontend":
      return '<span class="px-3 py-1 text-xs bg-blue-500/20 text-blue-300 rounded-full">Frontend</span>';
    case "Fullstack":
      return '<span class="px-3 py-1 text-xs bg-green-500/20 text-green-300 rounded-full">Fullstack</span>';
    default:
      return '<span class="px-3 py-1 text-xs bg-gray-500/20 text-gray-300 rounded-full">Other</span>';
  }
}

function renderProjectCard($title, $desc, $tech, $link, $icon, $category) {
  $techBadges = "";
  $bgColors = ["bg-purple-500/20 text-purple-300", "bg-blue-500/20 text-blue-300", "bg-green-500/20 text-green-300", "bg-yellow-500/20 text-yellow-300"];
  $i = 0;
  foreach ($tech as $t) {
    $color = $bgColors[$i % count($bgColors)];
    $techBadges .= '<span class="px-3 py-1 text-xs ' . $color . ' rounded-full">' . htmlspecialchars($t) . '</span>';
    $i++;
  }
  $categoryLabel = getCategoryLabel($category);
  return '
    <div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden hover:border-purple-500/50 transition-all duration-300">
      <div class="h-48 bg-gradient-to-br from-purple-900/40 to-blue-900/40 flex items-center justify-center">
        <span class="text-purple-400 text-lg">' . $icon . '</span>
      </div>
      <div class="p-6">
        <div class="flex items-center justify-between mb-2">
          <h3 class="text-xl font-bold text-white">' . htmlspecialchars($title) . '</h3>
          ' . $categoryLabel . '
        </div>
        <p class="text-gray-400 mb-4">' . htmlspecialchars($desc) . '</p>
        <div class="flex flex-wrap gap-2 mb-4">' . $techBadges . '</div>
        <div class="flex gap-4">
          <a href="' . $link . '" target="_blank" class="text-purple-400 hover:text-purple-300 transition-colors text-sm">🔗 GitHub →</a>
        </div>
      </div>
    </div>
  ';
}

function renderPortfolioSection($title, $items) {
  $id = strtolower($title);
  $output = '
  <section id="' . $id . '" class="py-20 bg-black">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">Featured <span class="text-purple-400">' . htmlspecialchars($title) . '</span></h2>
      <div class="project-grid grid md:grid-cols-2 gap-8">';
  foreach ($items as $item) {
    $output .= renderProjectCard($item["title"], $item["desc"], $item["tech"], $item["link"], $item["icon"], $item["category"]);
  }
  $output .= '
      </div>
    </div>
  </section>';
  return $output;
}

function renderSkillTree($tree, $depth = 0) {
  $output = "";
  $padding = $depth * 20;
  if (is_array($tree)) {
    $output .= '<ul class="space-y-1" style="padding-left: ' . $padding . 'px">';
    foreach ($tree as $key => $value) {
      if (is_array($value)) {
        $output .= '<li><span class="text-purple-400 font-medium">📂 ' . htmlspecialchars($key) . '</span>';
        $output .= renderSkillTree($value, $depth + 1);
        $output .= '</li>';
      } else {
        $output .= '<li style="padding-left: ' . ($padding + 20) . 'px"><span class="text-gray-400">📄 ' . htmlspecialchars($value) . '</span></li>';
      }
    }
    $output .= '</ul>';
  }
  return $output;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $nama; ?> | Portfolio</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💻</text></svg>" />
  <link rel="stylesheet" href="css/style.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <header class="fixed top-0 left-0 w-full z-50 backdrop-blur-md bg-black/60 border-b border-white/10">
    <nav class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
      <a href="#" class="text-xl font-bold text-white"><?php echo $nama[0]; ?>.</a>
      <ul class="desktop-nav hidden md:flex space-x-8 text-gray-300">
        <li><a href="#about" class="hover:text-white transition-colors">About</a></li>
        <li><a href="#skills" class="hover:text-white transition-colors">Skills</a></li>
        <li><a href="#projects" class="hover:text-white transition-colors">Projects</a></li>
        <li><a href="pages/dashboard.html" class="hover:text-white transition-colors">Dashboard</a></li>
        <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
      </ul>
      <button id="menu-btn" class="mobile-toggle md:hidden text-white focus:ring-2 focus:ring-purple-500 focus:outline-none rounded-lg p-1 transition-all">
        <svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </nav>
    <div id="mobile-menu" class="mobile-menu hidden md:hidden bg-black/90 border-t border-white/10">
      <ul class="flex flex-col items-center py-4 space-y-4 text-gray-300">
        <li><a href="#about" class="hover:text-white transition-colors">About</a></li>
        <li><a href="#skills" class="hover:text-white transition-colors">Skills</a></li>
        <li><a href="#projects" class="hover:text-white transition-colors">Projects</a></li>
        <li><a href="pages/dashboard.html" class="hover:text-white transition-colors">Dashboard</a></li>
        <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
      </ul>
    </div>
  </header>

  <section id="hero" class="min-h-screen flex items-center justify-center relative overflow-hidden bg-black">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-900/40 via-black to-blue-900/40 animate-gradient"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-purple-500/10 via-transparent to-transparent"></div>
    <div class="relative z-10 text-center px-6">
      <p class="text-purple-400 text-lg mb-4"><span id="greeting"></span>, I'm</p>
      <h1 class="text-5xl md:text-7xl font-bold mb-4 bg-gradient-to-r from-purple-400 via-pink-400 to-blue-400 bg-clip-text text-transparent">
        <?php echo $nama; ?>
      </h1>
      <p class="text-xl md:text-2xl text-gray-400 mb-6"><?php echo $role; ?></p>
      <?php if ($open_to_work): ?>
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-500/10 border border-green-500/30 rounded-full text-green-400 text-sm mb-8">
          <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
          Open to Work 🟢
        </div>
      <?php else: ?>
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-500/10 border border-red-500/30 rounded-full text-red-400 text-sm mb-8">
          <span class="w-2 h-2 bg-red-400 rounded-full"></span>
          Not Available 🔴
        </div>
      <?php endif; ?>
      <div class="flex gap-4 justify-center">
        <a href="#contact" class="px-8 py-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-full font-medium hover:scale-105 hover:shadow-lg hover:shadow-purple-500/25 transition-all duration-300">Contact Me</a>
        <a href="#projects" class="px-8 py-3 border border-gray-600 text-gray-300 rounded-full font-medium hover:border-purple-500 hover:text-purple-400 hover:scale-105 transition-all duration-300">View Projects</a>
      </div>
    </div>
  </section>

  <section id="about" class="py-20 bg-black">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">About <span class="text-purple-400">Me</span></h2>
      <div class="about-layout flex flex-col md:flex-row items-center gap-10">
        <div class="w-48 h-48 md:w-64 md:h-64 rounded-full border-4 border-purple-500/30 overflow-hidden flex-shrink-0">
          <img src="<?php echo $foto; ?>" alt="<?php echo $nama; ?>" class="w-full h-full object-cover" onerror="this.src='<?php echo $foto_fallback; ?>'" />
        </div>
        <div class="text-gray-400 text-lg leading-relaxed">
          <p class="mb-4"><?php echo $bio_1; ?></p>
          <p class="mb-4"><?php echo $bio_2; ?></p>
          <p class="mb-4"><?php echo $bio_3; ?></p>
          <p><?php echo $bio_4; ?></p>
        </div>
      </div>
    </div>
  </section>

  <section id="skills" class="py-20 bg-gray-950">
    <div class="max-w-6xl mx-auto px-6">
      <div class="flex items-center justify-between mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-white">My <span class="text-purple-400">Skills</span></h2>
        <a href="#skill-tree" class="text-purple-400 hover:text-purple-300 transition-colors text-sm">View Skill Tree →</a>
      </div>
      <div class="skill-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($skills as $index => $skill): ?>
        <div class="skill-card bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center hover:border-purple-500/50 hover:-translate-y-2 transition-all duration-300" style="animation-delay: <?php echo ($index + 1) * 0.1; ?>s">
          <div class="text-4xl mb-3"><?php echo $skill["icon"]; ?></div>
          <h3 class="text-white font-semibold mb-2"><?php echo htmlspecialchars($skill["name"]); ?></h3>
          <div class="w-full bg-gray-800 rounded-full h-2">
            <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full" style="width: <?php echo $skill["level"]; ?>%"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="reports" class="py-20 bg-gray-950">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-4">Skill <span class="text-purple-400">Reports</span></h2>
      <p class="text-gray-400 text-center mb-12 max-w-2xl mx-auto">Data visualisasi kemampuan dan progres belajar yang saya capai selama ini.</p>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
          <h3 class="text-white font-semibold text-center mb-4">Skill Level</h3>
          <div class="chart-container">
            <canvas id="barChart"></canvas>
          </div>
        </div>
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
          <h3 class="text-white font-semibold text-center mb-4">Learning Progress</h3>
          <div class="chart-container">
            <canvas id="lineChart"></canvas>
          </div>
        </div>
        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
          <h3 class="text-white font-semibold text-center mb-4">Tech Stack Distribution</h3>
          <div class="chart-container">
            <canvas id="pieChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php echo renderPortfolioSection("Projects", $projects); ?>

  <section id="skill-tree" class="py-20 bg-gray-950">
    <div class="max-w-4xl mx-auto px-6">
      <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">Skill <span class="text-purple-400">Tree</span></h2>
      <p class="text-gray-400 text-center mb-8 max-w-2xl mx-auto">Struktur hierarki kemampuan yang saya kuasai — dirender dengan fungsi rekursif PHP.</p>
      <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-8">
        <?php echo renderSkillTree($skillTree); ?>
      </div>
    </div>
  </section>

  <section id="contact" class="py-20 bg-gray-950">
    <div class="max-w-4xl mx-auto px-6">
      <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">Get In <span class="text-purple-400">Touch</span></h2>
      <form id="contactForm" class="max-w-xl mx-auto space-y-6">
        <div>
          <label for="name" class="block text-gray-400 mb-2">Name</label>
          <input type="text" id="name" required class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors" placeholder="Your Name" />
          <p class="error-text" id="nameError">Name must be at least 3 characters</p>
        </div>
        <div>
          <label for="email" class="block text-gray-400 mb-2">Email</label>
          <input type="email" id="email" required class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors" placeholder="your@email.com" />
          <p class="error-text" id="emailError">Please enter a valid email address</p>
        </div>
        <div>
          <label for="message" class="block text-gray-400 mb-2">Message</label>
          <textarea id="message" rows="5" required class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors resize-none" placeholder="Your message..."></textarea>
          <p class="error-text" id="messageError">Message cannot be empty</p>
        </div>
        <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-lg font-medium hover:scale-[1.02] hover:shadow-lg hover:shadow-purple-500/25 transition-all duration-300">Send Message</button>
      </form>
    </div>
  </section>

  <footer class="py-8 bg-black border-t border-gray-800">
    <div class="footer-layout max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
      <p class="text-gray-500 text-sm">&copy; <?php echo date("Y"); ?> <?php echo $nama; ?>. All rights reserved.</p>
      <div class="flex gap-6">
        <a href="<?php echo $github; ?>" class="text-gray-500 hover:text-purple-400 transition-colors text-lg">GitHub</a>
        <a href="<?php echo $linkedin; ?>" class="text-gray-500 hover:text-purple-400 transition-colors text-lg">LinkedIn</a>
        <a href="<?php echo $instagram; ?>" class="text-gray-500 hover:text-purple-400 transition-colors text-lg">Instagram</a>
      </div>
    </div>
  </footer>

  <script src="js/script.js"></script>
</body>
</html>
