<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/ceir_thanas.php';
require __DIR__ . '/../../templates/header.php';


// Allow ADMIN to access CEIR pages
if (($_SESSION['role'] ?? '') === 'ADMIN') {
  // admin allowed
} else {
  require_role('CEIR_USER');
}

$thana   = $_GET['thana'] ?? ($_POST['thana'] ?? '');
$ceir_id = isset($_GET['ceir_id']) ? (int)$_GET['ceir_id'] : (isset($_POST['ceir_id']) ? (int)$_POST['ceir_id'] : 0);

if (!isset($CEIR_TABLES[$thana]) || $ceir_id <= 0) {
  die('Invalid request.');
}

$table = $CEIR_TABLES[$thana];

// Fetch row (for confirmation + to get pdf path)
$stmt = $pdo->prepare("SELECT ceir_id, name, imei, date_of_complaint, pdf_attach
                       FROM {$table}
                       WHERE ceir_id = :id
                       LIMIT 1");
$stmt->execute([':id' => $ceir_id]);
$row = $stmt->fetch();

if (!$row) die('Record not found.');

function delete_pdf_if_exists(?string $pdfAttach): void {
  if (!$pdfAttach) return;

  // pdf_attach stored like: /uploads/ceir_pdfs/filename.pdf
  $baseDir = realpath(__DIR__ . '/../uploads/ceir_pdfs');
  $filePath = realpath(__DIR__ . '/..' . $pdfAttach);

  if ($baseDir && $filePath && strpos($filePath, $baseDir) === 0 && is_file($filePath)) {
    @unlink($filePath); // unlink deletes the file [web:443]
  }
}

// POST => delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // delete pdf first (optional order)
  delete_pdf_if_exists($row['pdf_attach'] ?? null);

  // delete db row
  $stmt = $pdo->prepare("DELETE FROM {$table} WHERE ceir_id = :id LIMIT 1");
  $stmt->execute([':id' => $ceir_id]); // prepared delete pattern [web:340]

  // PRG redirect (prevents double submit)
  $q = 'thana=' . urlencode($thana) . '&deleted=1';
  header('Location: ' . BASE_PATH . '/ceir/list.php?' . $q);
  exit; 
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Delete CEIR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Delete CEIR</h3>
    <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_PATH ?>/ceir/list.php?thana=<?= urlencode($thana) ?>">Back</a>
  </div>

  <div class="alert alert-warning">
    Are you sure you want to delete this CEIR record?
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <p class="mb-1"><strong>ID:</strong> <?= (int)$row['ceir_id'] ?></p>
      <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($row['name'] ?? '') ?></p>
      <p class="mb-1"><strong>IMEI:</strong> <?= htmlspecialchars($row['imei'] ?? '') ?></p>
      <p class="mb-0"><strong>Date:</strong> <?= htmlspecialchars($row['date_of_complaint'] ?? '') ?></p>
    </div>
  </div>

  <form method="post">
    <input type="hidden" name="thana" value="<?= htmlspecialchars($thana) ?>">
    <input type="hidden" name="ceir_id" value="<?= (int)$row['ceir_id'] ?>">

    <button type="submit" class="btn btn-danger">Yes, Delete</button>
    <a class="btn btn-secondary" href="<?= BASE_PATH ?>/ceir/list.php?thana=<?= urlencode($thana) ?>">Cancel</a>
  </form>

</div>
</body>
</html>
