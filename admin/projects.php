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
            <th class="text-left p-4">GitHub</th>
            <th class="text-left p-4">File</th>
            <th class="text-left p-4">Created At</th>
            <th class="text-left p-4">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows === 0): ?>
          <tr><td colspan="7" class="p-4 text-gray-500 text-center">No projects yet. <a href="add_project.php" class="text-purple-400 hover:underline">Add one!</a></td></tr>
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
              <?php if ($row['github_url']): ?>
                <a href="<?php echo htmlspecialchars($row['github_url']); ?>" target="_blank" class="text-purple-400 hover:text-purple-300 transition-colors text-xs">🔗 Repo</a>
              <?php else: ?>
                <span class="text-gray-600">—</span>
              <?php endif; ?>
            </td>
            <td class="p-4">
              <?php if ($row['file_path']): ?>
                <?php
                $ext = strtolower(pathinfo($row['file_path'], PATHINFO_EXTENSION));
                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                ?>
                <?php if ($isImg): ?>
                  <a href="../<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">
                    <img src="../<?php echo htmlspecialchars($row['file_path']); ?>" class="w-16 h-12 object-cover rounded-lg border border-gray-700 hover:opacity-80 transition-opacity" />
                  </a>
                <?php else: ?>
                  <a href="../<?php echo htmlspecialchars($row['file_path']); ?>" class="text-purple-400 hover:text-purple-300 transition-colors" download>📎 Download</a>
                <?php endif; ?>
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
