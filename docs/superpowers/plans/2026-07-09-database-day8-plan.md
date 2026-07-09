# Database Day 8 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Upgrade portfolio from hardcoded PHP arrays to MySQL CRUD with file upload, admin sidebar, and downloadable project files.

**Architecture:** Root `connection.php` for DB access; `admin/` folder with sidebar include for all admin pages; `uploads/` for project files; `index.php` queries DB instead of using arrays.

**Tech Stack:** PHP 8.4, MySQL 8.0, Tailwind CSS, Chart.js, mysqli

## Global Constraints
- MySQL user: root, password: admin123
- DB name: portfolio_db
- Table: projects (id, title, description, tech as JSON TEXT, category, file_path VARCHAR, created_at TIMESTAMP)
- No comments in code
- Branch: feature/database-day8
- No auto-push — wait for user confirmation

---

### Task 1: Create Database, Table & connection.php

**Files:**
- Create: `connection.php`
- Run: SQL script via bash

- [ ] **Step 1: Create database and table via bash**

```bash
mysql -u root -padmin123 -e "CREATE DATABASE IF NOT EXISTS portfolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -padmin123 -e "
CREATE TABLE IF NOT EXISTS portfolio_db.projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  tech TEXT NOT NULL,
  category VARCHAR(50) NOT NULL,
  file_path VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
```

Expected output: no errors

- [ ] **Step 2: Create connection.php**

```php
<?php
$host = 'localhost';
$db = 'portfolio_db';
$user = 'root';
$pass = 'admin123';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
```

- [ ] **Step 3: Test connection**

Run: `php -r "require 'connection.php'; echo 'OK';"`
Expected output: `OK`

- [ ] **Step 4: Commit**

```bash
git add connection.php && git commit -m "feat: add connection.php with mysqli to portfolio_db"
```

---

### Task 2: Create admin/sidebar.php

**Files:**
- Create: `admin/sidebar.php`

- [ ] **Step 1: Create admin/sidebar.php**

```php
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin | Jimi Firgo Dakhi</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚙️</text></svg>" />
  <link rel="stylesheet" href="../css/style.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-black text-white">
  <div class="flex min-h-screen">
    <aside class="w-64 bg-gray-950 border-r border-gray-800 flex flex-col fixed h-full z-40">
      <div class="p-6 border-b border-gray-800">
        <a href="../index.php" class="text-xl font-bold text-white">Jimi.</a>
        <p class="text-gray-500 text-xs mt-1">Admin Panel</p>
      </div>
      <nav class="flex-1 p-4 space-y-2">
        <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?php echo $current_page === 'index.php' ? 'bg-purple-500/20 text-purple-400' : 'text-gray-400 hover:text-white hover:bg-gray-900'; ?>">
          <span>📊</span> Dashboard
        </a>
        <a href="projects.php" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?php echo $current_page === 'projects.php' ? 'bg-purple-500/20 text-purple-400' : 'text-gray-400 hover:text-white hover:bg-gray-900'; ?>">
          <span>📋</span> Project List
        </a>
        <a href="add_project.php" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?php echo $current_page === 'add_project.php' ? 'bg-purple-500/20 text-purple-400' : 'text-gray-400 hover:text-white hover:bg-gray-900'; ?>">
          <span>➕</span> Add Project
        </a>
      </nav>
      <div class="p-4 border-t border-gray-800">
        <a href="../index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-900 transition-colors">
          <span>🔙</span> Back to Portfolio
        </a>
      </div>
    </aside>
    <main class="ml-64 flex-1 p-8">
```

Note: `</main></div></body></html>` will be in each page file.

- [ ] **Step 2: Commit**

```bash
git add admin/sidebar.php && git commit -m "feat: add admin sidebar layout"
```

---

### Task 3: Create admin/index.php (Dashboard)

**Files:**
- Create: `admin/index.php`

- [ ] **Step 1: Create admin/index.php**

```php
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
```

- [ ] **Step 2: Commit**

```bash
git add admin/index.php && git commit -m "feat: admin dashboard page with dynamic charts from DB"
```

---

### Task 4: Create admin/projects.php (Project List)

**Files:**
- Create: `admin/projects.php`

- [ ] **Step 1: Create admin/projects.php**

```php
<?php
require_once __DIR__ . '/sidebar.php';
require_once __DIR__ . '/../connection.php';

$result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
?>
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white">Project <span class="text-purple-400">List</span></h1>
        <p class="text-gray-400 mt-2">Manage all your portfolio projects.</p>
      </div>
      <a href="add_project.php" class="px-5 py-2.5 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-lg text-sm font-medium hover:scale-105 transition-all duration-300">➕ Add Project</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
    <div class="mb-6 px-4 py-3 bg-green-500/10 border border-green-500/30 rounded-lg text-green-400 text-sm"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
    <div class="mb-6 px-4 py-3 bg-red-500/10 border border-red-500/30 rounded-lg text-red-400 text-sm"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <div class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-800 text-gray-400">
            <th class="text-left p-4">No</th>
            <th class="text-left p-4">Title</th>
            <th class="text-left p-4">Category</th>
            <th class="text-left p-4">File</th>
            <th class="text-left p-4">Created At</th>
            <th class="text-left p-4">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows === 0): ?>
          <tr><td colspan="6" class="p-4 text-gray-500 text-center">No projects yet. <a href="add_project.php" class="text-purple-400 hover:underline">Add one!</a></td></tr>
          <?php endif; ?>
          <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
          <tr class="border-b border-gray-800/50 hover:bg-gray-900/30 transition-colors">
            <td class="p-4 text-gray-400"><?php echo $no++; ?></td>
            <td class="p-4 text-white font-medium"><?php echo htmlspecialchars($row['title']); ?></td>
            <td class="p-4"><?php
              $cat = $row['category'];
              $colors = ['Backend' => 'purple', 'Frontend' => 'blue', 'Fullstack' => 'green'];
              $c = $colors[$cat] ?? 'gray';
              echo '<span class="px-3 py-1 text-xs bg-' . $c . '-500/20 text-' . $c . '-300 rounded-full">' . htmlspecialchars($cat) . '</span>';
            ?></td>
            <td class="p-4">
              <?php if ($row['file_path']): ?>
                <a href="../<?php echo $row['file_path']; ?>" class="text-purple-400 hover:text-purple-300 transition-colors" download>📎 Download</a>
              <?php else: ?>
                <span class="text-gray-600">—</span>
              <?php endif; ?>
            </td>
            <td class="p-4 text-gray-400"><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
            <td class="p-4">
              <div class="flex gap-2">
                <a href="edit_project.php?id=<?php echo $row['id']; ?>" class="px-3 py-1.5 text-xs bg-blue-500/20 text-blue-300 rounded-lg hover:bg-blue-500/30 transition-colors">Edit</a>
                <form action="delete_project.php" method="POST" onsubmit="return confirm('Yakin hapus project ini?');">
                  <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
                  <button type="submit" class="px-3 py-1.5 text-xs bg-red-500/20 text-red-300 rounded-lg hover:bg-red-500/30 transition-colors">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main></div></body></html>
```

- [ ] **Step 2: Commit**

```bash
git add admin/projects.php && git commit -m "feat: admin project list table with edit/delete/download"
```

---

### Task 5: Create admin/delete_project.php

**Files:**
- Create: `admin/delete_project.php`

- [ ] **Step 1: Create admin/delete_project.php**

```php
<?php
require_once __DIR__ . '/../connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
  header('Location: projects.php?error=Invalid request');
  exit;
}

$id = (int)$_POST['id'];
$stmt = $conn->prepare("SELECT file_path FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

if (!$project) {
  header('Location: projects.php?error=Project not found');
  exit;
}

if ($project['file_path']) {
  $file = __DIR__ . '/../' . $project['file_path'];
  if (file_exists($file)) {
    unlink($file);
  }
}

$stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();

header('Location: projects.php?success=Project berhasil dihapus');
exit;
?>
```

- [ ] **Step 2: Commit**

```bash
git add admin/delete_project.php && git commit -m "feat: delete project with file removal"
```

---

### Task 6: Create admin/add_project.php

**Files:**
- Create: `admin/add_project.php`

- [ ] **Step 1: Create admin/add_project.php**

```php
<?php
require_once __DIR__ . '/sidebar.php';
require_once __DIR__ . '/../connection.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $tech_raw = trim($_POST['tech'] ?? '');
  $category = trim($_POST['category'] ?? '');

  if (empty($title)) $errors[] = 'Title wajib diisi.';
  if (empty($description)) $errors[] = 'Description wajib diisi.';
  if (empty($tech_raw)) $errors[] = 'Tech stack wajib diisi.';
  if (!in_array($category, ['Backend', 'Frontend', 'Fullstack'])) $errors[] = 'Pilih category yang valid.';

  $tech = json_encode(array_map('trim', explode(',', $tech_raw)));
  $file_path = null;

  if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
      $errors[] = 'Error upload file.';
    } else {
      $allowed = ['pdf', 'jpg', 'png'];
      $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, $allowed)) {
        $errors[] = 'Tipe file harus PDF, JPG, atau PNG.';
      } elseif ($_FILES['file']['size'] > 2 * 1024 * 1024) {
        $errors[] = 'Ukuran file maksimal 2MB.';
      } else {
        $upload_dir = __DIR__ . '/../uploads/';
        if (!is_dir($upload_dir)) {
          mkdir($upload_dir, 0777, true);
        }
        $filename = time() . '_' . basename($_FILES['file']['name']);
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
          $file_path = 'uploads/' . $filename;
        } else {
          $errors[] = 'Gagal menyimpan file.';
        }
      }
    }
  }

  if (empty($errors)) {
    $stmt = $conn->prepare("INSERT INTO projects (title, description, tech, category, file_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $tech, $category, $file_path);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header('Location: projects.php?success=Project berhasil ditambahkan');
    exit;
  }
}
?>
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white">Add <span class="text-purple-400">Project</span></h1>
      <p class="text-gray-400 mt-2">Tambahkan project baru ke portfolio.</p>
    </div>

    <?php if (!empty($errors)): ?>
    <div class="mb-6 px-4 py-3 bg-red-500/10 border border-red-500/30 rounded-lg">
      <?php foreach ($errors as $e): ?>
        <p class="text-red-400 text-sm">⚠️ <?php echo htmlspecialchars($e); ?></p>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-6">
      <div>
        <label class="block text-gray-400 mb-2">Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($title ?? ''); ?>" required class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors" placeholder="Nama project" />
      </div>
      <div>
        <label class="block text-gray-400 mb-2">Description</label>
        <textarea name="description" rows="4" required class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors resize-none" placeholder="Deskripsi project"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
      </div>
      <div>
        <label class="block text-gray-400 mb-2">Tech Stack</label>
        <input type="text" name="tech" value="<?php echo htmlspecialchars($tech_raw ?? ''); ?>" required class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors" placeholder="Pisahkan dengan koma, contoh: Laravel,PHP,MySQL" />
      </div>
      <div>
        <label class="block text-gray-400 mb-2">Category</label>
        <select name="category" required class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white focus:outline-none focus:border-purple-500 transition-colors">
          <option value="">— Select Category —</option>
          <option value="Backend" <?php echo ($category ?? '') === 'Backend' ? 'selected' : ''; ?>>Backend</option>
          <option value="Frontend" <?php echo ($category ?? '') === 'Frontend' ? 'selected' : ''; ?>>Frontend</option>
          <option value="Fullstack" <?php echo ($category ?? '') === 'Fullstack' ? 'selected' : ''; ?>>Fullstack</option>
        </select>
      </div>
      <div>
        <label class="block text-gray-400 mb-2">File (optional — PDF, JPG, PNG, max 2MB)</label>
        <input type="file" name="file" accept=".pdf,.jpg,.png" class="w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-500/20 file:text-purple-300 hover:file:bg-purple-500/30 transition-colors" />
      </div>
      <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-lg font-medium hover:scale-105 hover:shadow-lg hover:shadow-purple-500/25 transition-all duration-300">Simpan Project</button>
    </form>
  </main></div></body></html>
```

- [ ] **Step 2: Commit**

```bash
git add admin/add_project.php && git commit -m "feat: add project form with file upload and validation"
```

---

### Task 7: Create admin/edit_project.php (Bonus)

**Files:**
- Create: `admin/edit_project.php`

- [ ] **Step 1: Create admin/edit_project.php**

```php
<?php
require_once __DIR__ . '/sidebar.php';
require_once __DIR__ . '/../connection.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

if (!$project) {
  header('Location: projects.php?error=Project tidak ditemukan');
  exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $tech_raw = trim($_POST['tech'] ?? '');
  $category = trim($_POST['category'] ?? '');

  if (empty($title)) $errors[] = 'Title wajib diisi.';
  if (empty($description)) $errors[] = 'Description wajib diisi.';
  if (empty($tech_raw)) $errors[] = 'Tech stack wajib diisi.';
  if (!in_array($category, ['Backend', 'Frontend', 'Fullstack'])) $errors[] = 'Pilih category yang valid.';

  $tech = json_encode(array_map('trim', explode(',', $tech_raw)));
  $file_path = $project['file_path'];

  if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
      $errors[] = 'Error upload file.';
    } else {
      $allowed = ['pdf', 'jpg', 'png'];
      $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, $allowed)) {
        $errors[] = 'Tipe file harus PDF, JPG, atau PNG.';
      } elseif ($_FILES['file']['size'] > 2 * 1024 * 1024) {
        $errors[] = 'Ukuran file maksimal 2MB.';
      } else {
        if ($project['file_path']) {
          $old = __DIR__ . '/../' . $project['file_path'];
          if (file_exists($old)) unlink($old);
        }
        $upload_dir = __DIR__ . '/../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = time() . '_' . basename($_FILES['file']['name']);
        $dest = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
          $file_path = 'uploads/' . $filename;
        } else {
          $errors[] = 'Gagal menyimpan file.';
        }
      }
    }
  }

  if (empty($errors)) {
    $stmt = $conn->prepare("UPDATE projects SET title=?, description=?, tech=?, category=?, file_path=? WHERE id=?");
    $stmt->bind_param("sssssi", $title, $description, $tech, $category, $file_path, $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header('Location: projects.php?success=Project berhasil diupdate');
    exit;
  }
}

$tech_display = '';
if ($project['tech']) {
  $arr = json_decode($project['tech'], true);
  if (is_array($arr)) $tech_display = implode(',', $arr);
}
?>
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white">Edit <span class="text-purple-400">Project</span></h1>
      <p class="text-gray-400 mt-2">Update data project.</p>
    </div>

    <?php if (!empty($errors)): ?>
    <div class="mb-6 px-4 py-3 bg-red-500/10 border border-red-500/30 rounded-lg">
      <?php foreach ($errors as $e): ?>
        <p class="text-red-400 text-sm">⚠️ <?php echo htmlspecialchars($e); ?></p>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-6">
      <div>
        <label class="block text-gray-400 mb-2">Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? $project['title']); ?>" required class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors" />
      </div>
      <div>
        <label class="block text-gray-400 mb-2">Description</label>
        <textarea name="description" rows="4" required class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors resize-none"><?php echo htmlspecialchars($_POST['description'] ?? $project['description']); ?></textarea>
      </div>
      <div>
        <label class="block text-gray-400 mb-2">Tech Stack</label>
        <input type="text" name="tech" value="<?php echo htmlspecialchars($_POST['tech'] ?? $tech_display); ?>" required class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors" placeholder="Pisahkan dengan koma" />
      </div>
      <div>
        <label class="block text-gray-400 mb-2">Category</label>
        <select name="category" required class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white focus:outline-none focus:border-purple-500 transition-colors">
          <option value="Backend" <?php echo ($_POST['category'] ?? $project['category']) === 'Backend' ? 'selected' : ''; ?>>Backend</option>
          <option value="Frontend" <?php echo ($_POST['category'] ?? $project['category']) === 'Frontend' ? 'selected' : ''; ?>>Frontend</option>
          <option value="Fullstack" <?php echo ($_POST['category'] ?? $project['category']) === 'Fullstack' ? 'selected' : ''; ?>>Fullstack</option>
        </select>
      </div>
      <div>
        <label class="block text-gray-400 mb-2">File (biarkan kosong jika tidak ganti)</label>
        <input type="file" name="file" accept=".pdf,.jpg,.png" class="w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-500/20 file:text-purple-300 hover:file:bg-purple-500/30 transition-colors" />
        <?php if ($project['file_path']): ?>
          <p class="text-gray-500 text-xs mt-2">File saat ini: <a href="../<?php echo $project['file_path']; ?>" class="text-purple-400 hover:underline"><?php echo basename($project['file_path']); ?></a></p>
        <?php endif; ?>
      </div>
      <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-lg font-medium hover:scale-105 hover:shadow-lg hover:shadow-purple-500/25 transition-all duration-300">Update Project</button>
    </form>
  </main></div></body></html>
```

- [ ] **Step 2: Commit**

```bash
git add admin/edit_project.php && git commit -m "feat: edit project form with file update"
```

---

### Task 8: Seed Data — Insert 4 Original Projects into DB

**Files:**
- Create: `seed.php`

- [ ] **Step 1: Create seed.php**

```php
<?php
require_once __DIR__ . '/connection.php';

$projects = [
  ["LMS Royal Prima", "Learning Management System untuk mengelola pembelajaran, materi, dan evaluasi siswa secara terintegrasi.", ["Laravel", "PHP", "Blade", "MySQL"], "Fullstack"],
  ["Overtime Request System", "Sistem pengajuan lembur digital dengan approval workflow multi-level, perhitungan otomatis, dan pelaporan.", ["Laravel", "PHP", "Tailwind", "MySQL"], "Backend"],
  ["SimpleAttendance", "Sistem absensi berbasis web dengan fitur check-in/check-out, riwayat kehadiran, dan dashboard admin.", ["Laravel", "PHP", "Tailwind", "MySQL"], "Backend"],
  ["Antrian-Ku", "Sistem manajemen antrian digital untuk instansi pelayanan publik dengan Multi Channel Single Phase.", ["Laravel", "PHP", "Blade", "jQuery"], "Frontend"]
];

$stmt = $conn->prepare("INSERT INTO projects (title, description, tech, category) VALUES (?, ?, ?, ?)");
$count = 0;
foreach ($projects as $p) {
  $tech = json_encode($p[2]);
  $stmt->bind_param("ssss", $p[0], $p[1], $tech, $p[3]);
  $stmt->execute();
  $count++;
}
$stmt->close();
$conn->close();
echo "Seeded $count projects successfully.\n";
?>
```

- [ ] **Step 2: Run seed**

Run: `php seed.php`
Expected output: `Seeded 4 projects successfully.`

- [ ] **Step 3: Remove seed.php (cleanup)**

Run: `rm seed.php`

- [ ] **Step 4: Commit**

```bash
git add . && git commit -m "chore: seed database with initial projects"
```

---

### Task 9: Modify index.php — Replace Array with DB Query + Download Link

**Files:**
- Modify: `index.php`

- [ ] **Step 1: Replace the $projects array with DB query in index.php**

Replace lines 27-60 (the `$projects` array) with:
```php
require_once __DIR__ . '/connection.php';
$result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = [];
while ($row = $result->fetch_assoc()) {
  $tech = json_decode($row['tech'], true);
  if (!is_array($tech)) $tech = [];
  $projects[] = [
    "title" => $row['title'],
    "desc" => $row['description'],
    "tech" => $tech,
    "link" => $row['file_path'] ? $row['file_path'] : "#",
    "icon" => "📁",
    "category" => $row['category'],
    "file_path" => $row['file_path']
  ];
}
```

Also add `id` property for the delete flow and update `renderProjectCard` to show download link.

- [ ] **Step 2: Update renderProjectCard to accept file_path**

Modify `renderProjectCard()` signature and body:
```php
function renderProjectCard($title, $desc, $tech, $link, $icon, $category, $file_path = null) {
  // ... existing code ...
  $downloadLink = '';
  if ($file_path) {
    $downloadLink = '<a href="' . $file_path . '" download class="text-green-400 hover:text-green-300 transition-colors text-sm">📎 Download →</a>';
  }
  // Add downloadLink after GitHub link
  // in the <div class="flex gap-4"> section:
  // $output .= $downloadLink;
}
```

Also update `renderPortfolioSection` to pass `$item["file_path"]`.

- [ ] **Step 3: Remove Dashboard link from navbar (lines 175, 192)**

Delete both `<li><a href="pages/dashboard.html" ...>Dashboard</a></li>` lines.

- [ ] **Step 4: Commit**

```bash
git add index.php && git commit -m "feat: index.php now queries DB, download link added, dashboard nav removed"
```

---

### Task 10: Create Documentation

**Files:**
- Create: `docs/DOKUMENTASI-DATABASE.md`

- [ ] **Step 1: Create documentation file with full explanation for each grading criterion**

The documentation should cover:
1. Database structure explanation
2. connection.php how it works
3. Displaying data from DB (SELECT)
4. Add project form + upload flow (INSERT + mkdir + validation)
5. Delete flow (DELETE + unlink)
6. Download file link
7. Edit flow (UPDATE + file replace)
8. Folder structure
9. Deployment guide (InfinityFree / 000WebHost)

- [ ] **Step 2: Commit**

```bash
git add docs/DOKUMENTASI-DATABASE.md && git commit -m "docs: add database day 8 documentation"
```

---

### Task 11: Final Verification

- [ ] **Step 1: Start PHP built-in server and test all pages**

Run: `php -S localhost:8000` in background

Test:
- `http://localhost:8000/index.php` — portfolio loads projects from DB
- `http://localhost:8000/admin/index.php` — admin dashboard with charts
- `http://localhost:8000/admin/projects.php` — project list with edit/delete/download
- `http://localhost:8000/admin/add_project.php` — form with file upload
- `http://localhost:8000/admin/edit_project.php?id=1` — edit form pre-filled

- [ ] **Step 2: Show summary and ask for push confirmation**
