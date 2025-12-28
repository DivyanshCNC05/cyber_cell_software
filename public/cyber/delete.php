<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/thanas.php';

require_role('CYBER_USER');

$allowed = cyber_allowed_thanas_for_logged_user();
if (!$allowed) die('No thanas assigned.');

$thana = $_GET['thana'] ?? ($_POST['thana'] ?? '');
$sno   = isset($_GET['sno']) ? (int)$_GET['sno'] : (isset($_POST['sno']) ? (int)$_POST['sno'] : 0);

if (!in_array($thana, $allowed, true) || !isset($CYBER_TABLES[$thana])) {
    die('Invalid / unauthorized thana.');
}
if ($sno <= 0) die('Invalid S.No.');

$table = $CYBER_TABLES[$thana];

$error = '';
$success = '';

// 1) If POST => delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Delete record
    $stmt = $pdo->prepare("DELETE FROM {$table} WHERE sno = :sno LIMIT 1");
    $stmt->execute([':sno' => $sno]);

    // Redirect back to list
    header('Location: /cyber/list.php?thana=' . urlencode($thana) . '&deleted=1');
    exit;
}

// 2) If GET => fetch record and show confirmation page
$stmt = $pdo->prepare("SELECT sno, complaint_number, applicant_name, complaint_date
                       FROM {$table}
                       WHERE sno = :sno
                       LIMIT 1");
$stmt->execute([':sno' => $sno]);
$row = $stmt->fetch();

if (!$row) die('Record not found.');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Delete Complaint</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Delete Complaint</h3>
    <a class="btn btn-outline-secondary btn-sm" href="/cyber/list.php?thana=<?= urlencode($thana) ?>">Back</a>
  </div>

  <div class="alert alert-warning">
    Are you sure you want to delete this record?
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <p class="mb-1"><strong>Thana:</strong> <?= htmlspecialchars(cyber_thana_label($thana)) ?></p>
      <p class="mb-1"><strong>S.No:</strong> <?= (int)$row['sno'] ?></p>
      <p class="mb-1"><strong>Complaint No:</strong> <?= htmlspecialchars($row['complaint_number'] ?? '') ?></p>
      <p class="mb-0"><strong>Applicant:</strong> <?= htmlspecialchars($row['applicant_name'] ?? '') ?> (<?= htmlspecialchars($row['complaint_date'] ?? '') ?>)</p>
    </div>
  </div>

  <form method="post">
    <input type="hidden" name="thana" value="<?= htmlspecialchars($thana) ?>">
    <input type="hidden" name="sno" value="<?= (int)$row['sno'] ?>">

    <button type="submit" class="btn btn-danger">Yes, Delete</button>
    <a class="btn btn-secondary" href="/cyber/list.php?thana=<?= urlencode($thana) ?>">Cancel</a>
  </form>

</div>
</body>
</html>
