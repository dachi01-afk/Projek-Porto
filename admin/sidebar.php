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
