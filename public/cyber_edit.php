<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/tables.php';
require_role(['ADMIN','CYBER_USER']);
$pdo = getPDO();
$tables = get_cyber_tables();
$table = $_REQUEST['table'] ?? null;
$id = $_REQUEST['id'] ?? null;
if (!in_array($table, $tables)) {
    flash_set('error', 'Invalid table');
    redirect('/cyber_dashboard.php');
}
if (!$id || !is_numeric($id)) {
    flash_set('error', 'Invalid id');
    redirect('/cyber_list.php?table=' . urlencode($table));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? '')) {
        flash_set('error', 'Invalid CSRF token');
        redirect('/cyber_edit.php?table=' . urlencode($table) . '&id=' . urlencode($id));
    }
    $stmt = $pdo->prepare("UPDATE {$table} SET applicant_name = ?, nature_of_fraud = ?, incident_date = ?, total_fraud = ?, block_or_unblock = ? WHERE sno = ?");
    $stmt->execute([
        $_POST['applicant_name'] ?? null,
        $_POST['nature_of_fraud'] ?? null,
        $_POST['incident_date'] ?: null,
        $_POST['total_fraud'] ?: 0,
        $_POST['block_or_unblock'] ?? 'UNBLOCK',
        $id
    ]);
    flash_set('success', 'Record updated');
    redirect('/cyber_list.php?table=' . urlencode($table));
}

$stmt = $pdo->prepare("SELECT * FROM {$table} WHERE sno = ?");
$stmt->execute([$id]);
$record = $stmt->fetch();
if (!$record) {
    flash_set('error', 'Record not found');
    redirect('/cyber_list.php?table=' . urlencode($table));
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Record</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h3>Edit - <?= sanitize(pretty_thana($table)) ?></h3>
  <form method="post" action="/cyber_edit.php?table=<?= urlencode($table) ?>&id=<?= urlencode($id) ?>">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <div class="mb-3">
      <label class="form-label">Applicant</label>
      <input class="form-control" name="applicant_name" value="<?= sanitize($record['applicant_name'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Nature of fraud</label>
      <input class="form-control" name="nature_of_fraud" value="<?= sanitize($record['nature_of_fraud'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Incident date</label>
      <input type="date" class="form-control" name="incident_date" value="<?= sanitize($record['incident_date'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Total fraud amount</label>
      <input class="form-control" name="total_fraud" value="<?= sanitize($record['total_fraud'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Block/Unblock</label>
      <select name="block_or_unblock" class="form-select">
        <option value="UNBLOCK" <?= ($record['block_or_unblock'] ?? '') === 'UNBLOCK' ? 'selected' : '' ?>>UNBLOCK</option>
        <option value="BLOCK" <?= ($record['block_or_unblock'] ?? '') === 'BLOCK' ? 'selected' : '' ?>>BLOCK</option>
      </select>
    </div>
    <div>
      <button class="btn btn-primary">Save</button>
      <a class="btn btn-secondary" href="/cyber_list.php?table=<?= urlencode($table) ?>">Cancel</a>
    </div>
  </form>
</div>
</body>
</html>