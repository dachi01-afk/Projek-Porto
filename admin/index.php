<?php
require_once __DIR__ . '/sidebar.php';
require_once __DIR__ . '/../connection.php';

$projectCount = $conn->query("SELECT COUNT(*) as total FROM projects")->fetch_assoc()['total'];
$categoryCount = $conn->query("SELECT category, COUNT(*) as total FROM projects GROUP BY category");

$categories = [];
$catData = [];
while ($row = $categoryCount->fetch_assoc()) {
  $categories[] = $row['category'];
  $catData[] = (int)$row['total'];
}
?>
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white">Admin <span class="text-purple-400">Dashboard</span></h1>
      <p class="text-gray-400 mt-2">Overview of your portfolio projects.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
        <p class="text-gray-400 text-sm">Total Projects</p>
        <p class="text-3xl font-bold text-white mt-2"><?php echo $projectCount; ?></p>
      </div>
      <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
        <p class="text-gray-400 text-sm">Categories</p>
        <p class="text-3xl font-bold text-white mt-2"><?php echo count($categories); ?></p>
      </div>
      <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
        <p class="text-gray-400 text-sm">With Files</p>
        <p class="text-3xl font-bold text-white mt-2"><?php echo $conn->query("SELECT COUNT(*) as total FROM projects WHERE file_path IS NOT NULL")->fetch_assoc()['total']; ?></p>
      </div>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
      <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
        <h2 class="text-white font-semibold text-center mb-4">Category Distribution</h2>
        <div class="chart-container-wide">
          <canvas id="adminPieChart"></canvas>
        </div>
      </div>
      <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
        <h2 class="text-white font-semibold text-center mb-4">Projects Overview</h2>
        <div class="chart-container-wide">
          <canvas id="adminBarChart"></canvas>
        </div>
      </div>
    </div>

    <script>
    new Chart(document.getElementById('adminPieChart'), {
      type: 'pie',
      data: {
        labels: <?php echo json_encode($categories); ?>,
        datasets: [{
          data: <?php echo json_encode($catData); ?>,
          backgroundColor: ['rgba(168,85,247,0.7)', 'rgba(59,130,246,0.7)', 'rgba(34,197,94,0.7)'],
          borderColor: ['#a855f7', '#3b82f6', '#22c55e'],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { labels: { color: '#9ca3af', font: { size: 10 } } }
        }
      }
    });

    new Chart(document.getElementById('adminBarChart'), {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($categories); ?>,
        datasets: [{
          label: 'Projects',
          data: <?php echo json_encode($catData); ?>,
          backgroundColor: 'rgba(168,85,247,0.8)',
          borderColor: '#a855f7',
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
            ticks: { stepSize: 1, color: '#9ca3af' },
            grid: { color: 'rgba(255,255,255,0.05)' }
          },
          x: {
            ticks: { color: '#9ca3af' },
            grid: { display: false }
          }
        }
      }
    });
    </script>
  </main></div></body></html>
