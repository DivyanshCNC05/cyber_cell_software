<?php
require __DIR__ . '../../includes/db.php';
require __DIR__ . '../../includes/auth.php';
require __DIR__ . '../../includes/thanas.php';

require_role('CYBER_USER');

$allowed = cyber_allowed_thanas_for_logged_user();
if (!$allowed) {
  die('No thanas assigned to this user.');
}

// selected thana (default = first allowed)
$thana = $_GET['thana'] ?? $allowed[0];
$deleted = isset($_GET['deleted']);
$updated = isset($_GET['updated']);


if (!in_array($thana, $allowed, true) || !isset($CYBER_TABLES[$thana])) {
  $thana = $allowed[0];
}

$table = $CYBER_TABLES[$thana];

// optional date filters
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';

// Build query
$where = [];
$params = [];

if ($from !== '') { $where[] = "complaint_date >= :from"; $params[':from'] = $from; }
if ($to !== '')   { $where[] = "complaint_date <= :to";   $params[':to']   = $to; }

$whereSql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

// List last 200 records
$sql = "SELECT sno, complaint_number, applicant_name, acknowledgement_number,
               complaint_date, total_fraud, hold_amount, refund_amount,
               fraud_mobile_number, block_or_unblock, created_at
        FROM {$table}
        {$whereSql}
        ORDER BY sno DESC
        LIMIT 200";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(); // returns array of rows [web:317]
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cyber Complaints List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Cyber Complaints - <?= htmlspecialchars(cyber_thana_label($thana)) ?></h3>
    <div>
      <a class="btn btn-primary btn-sm" href="/cyber/add.php">Add New</a>
      <a class="btn btn-outline-secondary btn-sm" href="/dashboards/user<?= (int)($_SESSION['user_number'] ?? 1) ?>.php">Back</a>
    </div>
  </div>

  <form class="row g-2 mb-3" method="get">
    <div class="col-md-4">
      <select class="form-select" name="thana">
        <?php foreach ($allowed as $k): ?>
          <option value="<?= htmlspecialchars($k) ?>" <?= $k === $thana ? 'selected' : '' ?>>
            <?= htmlspecialchars(cyber_thana_label($k)) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3">
      <input type="date" class="form-control" name="from" value="<?= htmlspecialchars($from) ?>" placeholder="From">
    </div>

    <div class="col-md-3">
      <input type="date" class="form-control" name="to" value="<?= htmlspecialchars($to) ?>" placeholder="To">
    </div>

    <div class="col-md-2 d-grid">
      <button class="btn btn-dark" type="submit">Filter</button>
    </div>
  </form>

  <?php if ($updated): ?><div class="alert alert-success">Record updated.</div><?php endif; ?>
<?php if ($deleted): ?><div class="alert alert-success">Record deleted.</div><?php endif; ?>


  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>S.No</th>
          <th>Complaint No</th>
          <th>Applicant</th>
          <th>Ack No</th>
          <th>Complaint Date</th>
          <th>Total Fraud</th>
          <th>Hold</th>
          <th>Refund</th>
          <th>Fraud Mobile</th>
          <th>Block</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$rows): ?>
        <tr><td colspan="11" class="text-center">No records found</td></tr>
      <?php else: ?>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['sno'] ?></td>
            <td><?= htmlspecialchars($r['complaint_number'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['applicant_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['acknowledgement_number'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['complaint_date'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['total_fraud'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['hold_amount'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['refund_amount'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['fraud_mobile_number'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['block_or_unblock'] ?? '') ?></td>
            <td>
              <a class="btn btn-sm btn-warning"
                 href="/cyber/edit.php?thana=<?= urlencode($thana) ?>&sno=<?= (int)$r['sno'] ?>">Edit</a>
              <a class="btn btn-sm btn-danger"
                 href="/cyber/delete.php?thana=<?= urlencode($thana) ?>&sno=<?= (int)$r['sno'] ?>">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <p class="text-muted small mb-0">Showing latest 200 records (sorted by S.No descending).</p>

</div>
</body>
</html>
