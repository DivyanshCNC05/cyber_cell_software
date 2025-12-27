<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/tables.php';
require_role(['ADMIN','CYBER_USER']);
$user = current_user();
$tables = get_cyber_tables();
$selected = $_GET['table'] ?? ($tables[0] ?? null);
if (!in_array($selected, $tables)) {
    flash_set('error', 'Invalid table selected');
    redirect('/cyber_dashboard.php');
}
$pdo = getPDO();
$stmt = $pdo->prepare("SELECT * FROM {$selected} ORDER BY sno DESC LIMIT 200");
$stmt->execute();
$rows = $stmt->fetchAll();
$error = flash_get('error');
$success = flash_get('success');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cyber Records - <?= sanitize(pretty_thana($selected)) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3><?= sanitize(pretty_thana($selected)) ?> - Records</h3>
    <div>
      <form method="get" class="d-inline">
        <select name="table" onchange="this.form.submit()" class="form-select">
          <?php foreach ($tables as $t): ?>
            <option value="<?= sanitize($t) ?>" <?= $t === $selected ? 'selected' : '' ?>><?= sanitize(pretty_thana($t)) ?></option>
          <?php endforeach; ?>
        </select>
      </form>
    </div>
  </div>

  <?php if ($error): ?><div class="alert alert-danger"><?= sanitize($error) ?></div><?php endif; ?>
  <?php if ($success): ?><div class="alert alert-success"><?= sanitize($success) ?></div><?php endif; ?>

  <p><a class="btn btn-primary" href="/cyber_complaint_form.php">Add New</a></p>

  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Complaint No</th>
          <th>Applicant</th>
          <th>Nature</th>
          <th>Incident</th>
          <th>Amount</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= sanitize($r['sno']) ?></td>
            <td><?= sanitize($r['complaint_number'] ?? '') ?></td>
            <td><?= sanitize($r['applicant_name'] ?? '') ?></td>
            <td><?= sanitize($r['nature_of_fraud'] ?? '') ?></td>
            <td><?= sanitize($r['incident_date'] ?? '') ?></td>
            <td><?= sanitize($r['total_fraud'] ?? '') ?></td>
            <td>
              <a class="btn btn-sm btn-secondary" href="/cyber_edit.php?table=<?= urlencode($selected) ?>&id=<?= urlencode($r['sno']) ?>">Edit</a>
              <?php if (is_admin()): ?>
              <form method="post" action="/cyber_delete.php" style="display:inline" onsubmit="return confirm('Delete this record?');">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="table" value="<?= sanitize($selected) ?>">
                <input type="hidden" name="id" value="<?= sanitize($r['sno']) ?>">
                <button class="btn btn-sm btn-danger">Delete</button>
              </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>