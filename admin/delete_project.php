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
