/* ============================================
   JavaScript Portfolio - Bootcamp Task 2
   Concepts: variables, data types, operators,
   conditional, looping, DOM manipulation,
   event handling, Chart.js, scriptable options
   ============================================ */

// ========== 1. VARIABLES, DATA TYPES & OPERATORS ==========

const portfolioOwner = 'Jimi Firgo Dakhi';
let currentYear = 2026;
const isBootcampComplete = false;

const skillsData = [
  { name: 'PHP / Laravel', level: 92, icon: '🐘' },
  { name: 'HTML & CSS', level: 85, icon: '🟢' },
  { name: 'Tailwind CSS', level: 82, icon: '🎨' },
  { name: 'JavaScript', level: 75, icon: '💻' },
  { name: 'MySQL', level: 85, icon: '🗄️' },
  { name: 'Blade Templating', level: 88, icon: '📄' },
  { name: 'REST API', level: 80, icon: '🔗' },
  { name: 'Git & GitHub', level: 90, icon: '🐙' }
];

const learningData = [
  { week: 'Week 1', html: 40, css: 35, js: 10 },
  { week: 'Week 2', html: 60, css: 50, js: 20 },
  { week: 'Week 3', html: 75, css: 65, js: 35 },
  { week: 'Week 4', html: 85, css: 75, js: 50 },
  { week: 'Week 5', html: 90, css: 82, js: 65 },
  { week: 'Week 6', html: 92, css: 85, js: 75 }
];

const techStackData = [
  { label: 'Laravel', percentage: 35, color: '#a855f7' },
  { label: 'PHP', percentage: 20, color: '#3b82f6' },
  { label: 'Blade', percentage: 15, color: '#22c55e' },
  { label: 'Tailwind', percentage: 12, color: '#06b6d4' },
  { label: 'MySQL', percentage: 10, color: '#eab308' },
  { label: 'Others', percentage: 8, color: '#6b7280' }
];

console.log('Portfolio Owner:', portfolioOwner);
console.log('Skills Data:', skillsData);
console.log('Learning Data:', learningData);

// ========== 2. CONDITIONAL — GREETING BASED ON TIME ==========

function setGreeting() {
  const greetingEl = document.getElementById('greeting');
  if (!greetingEl) return;

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

  greetingEl.textContent = greeting;
  console.log(`Greeting set: "${greeting}" at hour ${hour}`);
}

setGreeting();

// ========== 3. LOOPING — RENDER SKILL BARS DYNAMICALLY ==========

function renderSkillBars() {
  const container = document.querySelector('.skill-grid');
  if (!container) return;

  container.innerHTML = '';

  skillsData.forEach((skill, index) => {
    const card = document.createElement('div');
    card.className = 'skill-card bg-gray-900/50 border border-gray-800 rounded-xl p-6 text-center hover:border-purple-500/50 hover:-translate-y-2 transition-all duration-300';
    card.style.animationDelay = `${(index + 1) * 0.1}s`;

    const levelPercent = skill.level;

    card.innerHTML = `
      <div class="text-4xl mb-3">${skill.icon}</div>
      <h3 class="text-white font-semibold mb-2">${skill.name}</h3>
      <div class="w-full bg-gray-800 rounded-full h-2">
        <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full skill-bar" style="width: 0%" data-target="${levelPercent}"></div>
      </div>
    `;

    container.appendChild(card);
  });

  setTimeout(animateSkillBars, 300);
}

function animateSkillBars() {
  const bars = document.querySelectorAll('.skill-bar');
  bars.forEach(bar => {
    const target = parseInt(bar.dataset.target);
    bar.style.width = target + '%';
  });
}

renderSkillBars();

// ========== 4. DOM MANIPULATION — MOBILE MENU TOGGLE ==========

function initMobileMenu() {
  const menuBtn = document.getElementById('menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const iconOpen = document.getElementById('menu-icon-open');
  const iconClose = document.getElementById('menu-icon-close');

  if (!menuBtn || !mobileMenu) return;

  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
    iconOpen.classList.toggle('hidden');
    iconClose.classList.toggle('hidden');
  });

  mobileMenu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu.classList.add('hidden');
      iconOpen.classList.remove('hidden');
      iconClose.classList.add('hidden');
    });
  });
}

initMobileMenu();

// ========== 5. EVENT HANDLING — FORM VALIDATION ==========

function showToast(message, type) {
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.classList.add('show'), 10);
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 400);
  }, 3000);
}

function validateForm() {
  const form = document.getElementById('contactForm');
  if (!form) return;

  const nameInput = document.getElementById('name');
  const emailInput = document.getElementById('email');
  const messageInput = document.getElementById('message');
  const nameError = document.getElementById('nameError');
  const emailError = document.getElementById('emailError');
  const messageError = document.getElementById('messageError');

  function validateField() {
    let isValid = true;

    if (nameInput.value.trim().length < 3) {
      nameInput.classList.add('form-error');
      nameError.classList.add('visible');
      isValid = false;
    } else {
      nameInput.classList.remove('form-error');
      nameError.classList.remove('visible');
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(emailInput.value.trim())) {
      emailInput.classList.add('form-error');
      emailError.classList.add('visible');
      isValid = false;
    } else {
      emailInput.classList.remove('form-error');
      emailError.classList.remove('visible');
    }

    if (messageInput.value.trim() === '') {
      messageInput.classList.add('form-error');
      messageError.classList.add('visible');
      isValid = false;
    } else {
      messageInput.classList.remove('form-error');
      messageError.classList.remove('visible');
    }

    return isValid;
  }

  nameInput.addEventListener('change', validateField);
  emailInput.addEventListener('change', validateField);
  messageInput.addEventListener('change', validateField);

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    if (validateField()) {
      console.log('Form submitted successfully');
      console.log('Name:', nameInput.value.trim());
      console.log('Email:', emailInput.value.trim());
      showToast('Thank you! Your message has been sent.', 'success');
      form.reset();
    } else {
      showToast('Please fix the errors before submitting.', 'error');
    }
  });
}

validateForm();

// ========== 6. CHART.JS — REPORTING CHARTS ==========

let barChart, lineChart, pieChart;

function initCharts() {
  const isDark = true;
  const gridColor = 'rgba(255,255,255,0.05)';
  const textColor = '#9ca3af';

  // --- Bar Chart ---
  const barCtx = document.getElementById('barChart');
  if (barCtx) {
    barChart = new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: skillsData.map(s => s.name),
        datasets: [{
          label: 'Skill Level (%)',
          data: skillsData.map(s => s.level),
          backgroundColor: skillsData.map(s => {
            if (s.level >= 90) return 'rgba(168, 85, 247, 0.8)';
            if (s.level >= 80) return 'rgba(59, 130, 246, 0.8)';
            if (s.level >= 70) return 'rgba(34, 197, 94, 0.8)';
            return 'rgba(234, 179, 8, 0.8)';
          }),
          borderColor: skillsData.map(s => {
            if (s.level >= 90) return '#a855f7';
            if (s.level >= 80) return '#3b82f6';
            if (s.level >= 70) return '#22c55e';
            return '#eab308';
          }),
          borderWidth: 2,
          borderRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            grid: { color: gridColor },
            ticks: { color: textColor, font: { size: 10 } }
          },
          x: {
            grid: { display: false },
            ticks: { color: textColor, font: { size: 9 } }
          }
        }
      }
    });
  }

  // --- Line Chart ---
  const lineCtx = document.getElementById('lineChart');
  if (lineCtx) {
    lineChart = new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: learningData.map(d => d.week),
        datasets: [
          {
            label: 'HTML',
            data: learningData.map(d => d.html),
            borderColor: '#a855f7',
            backgroundColor: 'rgba(168, 85, 247, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 3
          },
          {
            label: 'CSS',
            data: learningData.map(d => d.css),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 3
          },
          {
            label: 'JavaScript',
            data: learningData.map(d => d.js),
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 3
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: { color: textColor, font: { size: 10 } }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            grid: { color: gridColor },
            ticks: { color: textColor, font: { size: 10 } }
          },
          x: {
            grid: { color: gridColor },
            ticks: { color: textColor, font: { size: 9 } }
          }
        }
      }
    });
  }

  // --- Pie Chart ---
  const pieCtx = document.getElementById('pieChart');
  if (pieCtx) {
    pieChart = new Chart(pieCtx, {
      type: 'pie',
      data: {
        labels: techStackData.map(d => d.label),
        datasets: [{
          data: techStackData.map(d => d.percentage),
          backgroundColor: techStackData.map(d => d.color + 'CC'),
          borderColor: techStackData.map(d => d.color),
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { color: textColor, font: { size: 10 }, padding: 8 }
          }
        }
      }
    });
  }

  // --- Scatter Chart ---
  const scatterCtx = document.getElementById('scatterChart');
  if (scatterCtx) {
    const projects = ['LMS', 'Overtime', 'Attendance', 'Antrian'];
    const allPoints = [];
    projects.forEach((name, i) => {
      for (let j = 0; j < 5; j++) {
        allPoints.push({
          x: i + 1 + (Math.random() - 0.5) * 0.6,
          y: 20 + Math.random() * 60
        });
      }
    });

    scatterChart = new Chart(scatterCtx, {
      type: 'scatter',
      data: {
        datasets: [{
          label: 'Commit Activity',
          data: allPoints,
          backgroundColor: 'rgba(168, 85, 247, 0.6)',
          borderColor: '#a855f7',
          borderWidth: 1,
          pointRadius: 5
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            type: 'linear',
            position: 'bottom',
            min: 0.5,
            max: 4.5,
            grid: { color: gridColor },
            ticks: {
              color: textColor,
              callback: function(value) { return projects[Math.round(value) - 1] || ''; }
            },
            title: {
              display: true,
              text: 'Projects',
              color: textColor
            }
          },
          y: {
            beginAtZero: true,
            grid: { color: gridColor },
            ticks: { color: textColor },
            title: {
              display: true,
              text: 'Effort (hours)',
              color: textColor
            }
          }
        },
        plugins: {
          legend: {
            labels: { color: textColor }
          }
        }
      }
    });
  }
}

initCharts();

// ========== 7. DEBUGGING — CONSOLE LOGGING ==========

console.log('%c Portfolio JS Loaded ', 'background: #a855f7; color: white; font-size: 16px; padding: 4px;');
console.log('Skills count:', skillsData.length);
console.log('Learning weeks:', learningData.length);
console.log('Browser:', navigator.userAgent);
console.log('Page loaded at:', new Date().toISOString());
