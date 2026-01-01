<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/thanas.php';
require __DIR__ . '/../../templates/header.php';


// Allow ADMIN to act as a user if as_user is provided
if (($_SESSION['role'] ?? '') === 'ADMIN' && isset($_REQUEST['as_user'])) {
  $acting_user = (int)($_REQUEST['as_user']);
} else {
  require_role('CYBER_USER');
  $acting_user = (int)($_SESSION['user_number'] ?? 0);
}

$allowed = cyber_allowed_thanas_for_logged_user();
if (!$allowed) die('No thanas assigned.');

$thana = $_GET['thana'] ?? ($_POST['thana'] ?? '');
$sno   = isset($_GET['sno']) ? (int)$_GET['sno'] : (isset($_POST['sno']) ? (int)$_POST['sno'] : 0);

if (!in_array($thana, $allowed, true) || !isset($CYBER_TABLES[$thana])) {
  die('Invalid / unauthorized thana.');
}
if ($sno <= 0) die('Invalid S.No.');

$table = $CYBER_TABLES[$thana];

/**
 * 1) POST => delete
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // (Optional safety) Ensure the hidden thana/sno weren't tampered
  $postThana = $_POST['thana'] ?? '';
  $postSno   = (int)($_POST['sno'] ?? 0);
  if ($postThana !== $thana || $postSno !== $sno) {
    die('Invalid request.');
  }

  $stmt = $pdo->prepare("DELETE FROM {$table} WHERE sno = :sno LIMIT 1");
  $stmt->execute([':sno' => $sno]);

  // Redirect back to list
  $q = 'thana=' . urlencode($thana) . '&deleted=1';
  if (!empty($acting_user)) { $q .= '&as_user=' . (int)$acting_user; }

  header('Location: ' . rtrim(BASE_PATH, '/') . '/cyber/list.php?' . $q);
  exit; // stop execution after redirect [web:55]
}

/**
 * 2) GET => fetch record and show confirmation page
 */
$stmt = $pdo->prepare(
  "SELECT sno, complaint_number, applicant_name, complaint_date
   FROM {$table}
   WHERE sno = :sno
   LIMIT 1"
);
$stmt->execute([':sno' => $sno]);
$row = $stmt->fetch(PDO::FETCH_ASSOC); // associative array [web:82]

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
    <a class="btn btn-outline-secondary btn-sm"
       href="<?= BASE_PATH ?>/cyber/list.php?thana=<?= urlencode($thana) ?><?= !empty($acting_user) ? '&as_user=' . (int)$acting_user : '' ?>">
      Back
    </a>
  </div>

  <div class="alert alert-warning">
    Are you sure you want to delete this record?
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <p class="mb-1"><strong>Thana:</strong> <?= htmlspecialchars(cyber_thana_label($thana)) ?></p>
      <p class="mb-1"><strong>S.No:</strong> <?= (int)$row['sno'] ?></p>
      <p class="mb-1"><strong>Complaint No:</strong> <?= htmlspecialchars($row['complaint_number'] ?? '') ?></p>
      <p class="mb-0"><strong>Applicant:</strong> <?= htmlspecialchars($row['applicant_name'] ?? '') ?>
        (<?= htmlspecialchars($row['complaint_date'] ?? '') ?>)
      </p>
    </div>
  </div>

  <form method="post">
    <input type="hidden" name="thana" value="<?= htmlspecialchars($thana) ?>">
    <input type="hidden" name="sno" value="<?= (int)$row['sno'] ?>">
    <input type="hidden" name="as_user" value="<?= htmlspecialchars((string)$acting_user) ?>">

    <button type="submit" class="btn btn-danger">Yes, Delete</button>
    <a class="btn btn-secondary"
       href="<?= BASE_PATH ?>/cyber/list.php?thana=<?= urlencode($thana) ?><?= !empty($acting_user) ? '&as_user=' . (int)$acting_user : '' ?>">
      Cancel
    </a>
  </form>

</div>
</body>
</html>
