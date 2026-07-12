function showToast(message, type) {
  const colors = {
    success: 'from-green-500 to-green-600',
    error: 'from-red-500 to-red-600'
  };

  const $toast = $('<div>')
    .addClass('fixed bottom-8 right-8 px-6 py-3 rounded-xl text-white font-medium bg-gradient-to-r ' + colors[type] + ' transform translate-y-24 opacity-0 transition-all duration-400 z-50')
    .text(message)
    .appendTo('body');

  setTimeout(function () {
    $toast.css({ 'transform': 'translateY(0)', 'opacity': '1' });
  }, 10);

  setTimeout(function () {
    $toast.css({ 'transform': 'translateY(24px)', 'opacity': '0' });
    setTimeout(function () { $toast.remove(); }, 400);
  }, 3000);
}

const projectData = [
  { name: 'LMS Royal Prima', backend: 45, frontend: 30, database: 25 },
  { name: 'Overtime System', backend: 55, frontend: 25, database: 20 },
  { name: 'SimpleAttendance', backend: 40, frontend: 35, database: 25 },
  { name: 'Antrian-Ku', backend: 50, frontend: 30, database: 20 }
];

const textColor = '#9ca3af';
const gridColor = 'rgba(255,255,255,0.05)';

const stackedCtx = document.getElementById('stackedBarChart');
let stackedBarChart;

if (stackedCtx) {
  stackedBarChart = new Chart(stackedCtx, {
    type: 'bar',
    data: {
      labels: projectData.map(p => p.name),
      datasets: [
        {
          label: 'Backend',
          data: projectData.map(p => p.backend),
          backgroundColor: 'rgba(168, 85, 247, 0.8)',
          borderColor: '#a855f7',
          borderWidth: 1
        },
        {
          label: 'Frontend',
          data: projectData.map(p => p.frontend),
          backgroundColor: 'rgba(59, 130, 246, 0.8)',
          borderColor: '#3b82f6',
          borderWidth: 1
        },
        {
          label: 'Database',
          data: projectData.map(p => p.database),
          backgroundColor: 'rgba(34, 197, 94, 0.8)',
          borderColor: '#22c55e',
          borderWidth: 1
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          stacked: true,
          grid: { display: false },
          ticks: { color: textColor, font: { size: 9 } }
        },
        y: {
          stacked: true,
          beginAtZero: true,
          grid: { color: gridColor },
          ticks: { color: textColor, font: { size: 10 } }
        }
      },
      plugins: {
        legend: {
          labels: { color: textColor, font: { size: 10 } }
        }
      }
    }
  });
}

const polarCtx = document.getElementById('polarChart');
let polarChart;

if (polarCtx) {
  polarChart = new Chart(polarCtx, {
    type: 'polarArea',
    data: {
      labels: ['LMS Royal Prima', 'Overtime System', 'SimpleAttendance', 'Antrian-Ku'],
      datasets: [{
        data: [100, 100, 100, 100],
        backgroundColor: [
          'rgba(168, 85, 247, 0.7)',
          'rgba(59, 130, 246, 0.7)',
          'rgba(34, 197, 94, 0.7)',
          'rgba(234, 179, 8, 0.7)'
        ],
        borderColor: ['#a855f7', '#3b82f6', '#22c55e', '#eab308'],
        borderWidth: 2
      }]
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
        r: {
          grid: { color: gridColor },
          angleLines: { color: gridColor },
          ticks: {
            color: textColor,
            backdropColor: 'transparent',
            font: { size: 10 }
          }
        }
      }
    }
  });
}

function refreshDashboard() {
  if (stackedBarChart) {
    stackedBarChart.data.datasets.forEach(dataset => {
      dataset.data = dataset.data.map(() => Math.floor(20 + Math.random() * 50));
    });
    stackedBarChart.update();
  }

  if (polarChart) {
    polarChart.data.datasets[0].data = polarChart.data.datasets[0].data.map(() => 50 + Math.random() * 100);
    polarChart.update();
  }

  showToast('Dashboard data refreshed!', 'success');
  console.log('Dashboard refreshed at', new Date().toLocaleTimeString());
}

$('#refreshDashboard').on('click', refreshDashboard);

console.log('%c Dashboard JS Loaded (jQuery) ', 'background: #a855f7; color: white; font-size: 16px; padding: 4px;');
console.log('Project data:', projectData);
console.log('Page loaded at:', new Date().toISOString());
