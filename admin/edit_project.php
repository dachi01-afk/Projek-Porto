<?php
require_once __DIR__ . '/sidebar.php';
require_once __DIR__ . '/../connection.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();
$stmt->close();

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
  $github_url = trim($_POST['github_url'] ?? '');

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
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
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
    $stmt = $conn->prepare("UPDATE projects SET title=?, description=?, tech=?, category=?, github_url=?, file_path=? WHERE id=?");
    $stmt->bind_param("ssssssi", $title, $description, $tech, $category, $github_url, $file_path, $id);
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
        <label class="block text-gray-400 mb-2">GitHub URL</label>
        <input type="url" name="github_url" value="<?php echo htmlspecialchars($_POST['github_url'] ?? $project['github_url'] ?? ''); ?>" class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-purple-500 transition-colors" placeholder="https://github.com/username/repo" />
      </div>
      <div>
        <label class="block text-gray-400 mb-2">File (biarkan kosong jika tidak ganti)</label>
        <input type="file" name="file" accept=".pdf,.jpg,.png" class="w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-500/20 file:text-purple-300 hover:file:bg-purple-500/30 transition-colors" />
        <?php if ($project['file_path']): ?>
          <p class="text-gray-500 text-xs mt-2">File saat ini: <a href="../<?php echo htmlspecialchars($project['file_path']); ?>" class="text-purple-400 hover:underline"><?php echo basename($project['file_path']); ?></a></p>
        <?php endif; ?>
      </div>
      <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-lg font-medium hover:scale-105 hover:shadow-lg hover:shadow-purple-500/25 transition-all duration-300">Update Project</button>
    </form>
  </main></div></body></html>
