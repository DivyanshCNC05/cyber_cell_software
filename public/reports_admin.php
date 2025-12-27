<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/tables.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['ADMIN']);
$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;
$print = isset($_GET['print']);
$list = report_admin_by_thana($from, $to);
$gt = ['complaint_count'=>0,'total_fraud'=>0,'total_hold'=>0];
foreach ($list as $r) {
    $gt['complaint_count'] += (int)($r['complaint_count'] ?? 0);
    $gt['total_fraud'] += (float)($r['total_fraud'] ?? 0);
    $gt['total_hold'] += (float)($r['total_hold'] ?? 0);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Report</title>
  <?php if (!$print): ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <?php endif; ?>
</head>
<body <?php if ($print) echo 'onload="window.print()"'; ?> class="bg-light">
<div class="container py-4">
  <h3>Admin Report</h3>
  <form class="row g-2 mb-3" method="get">
    <div class="col-auto">
      <label class="form-label">From</label>
      <input type="date" class="form-control" name="from" value="<?= sanitize($from) ?>">
    </div>
    <div class="col-auto">
      <label class="form-label">To</label>
      <input type="date" class="form-control" name="to" value="<?= sanitize($to) ?>">
    </div>
    <div class="col-auto align-self-end">
      <button class="btn btn-primary">Filter</button>
      <a class="btn btn-outline-secondary" href="?<?= http_build_query(array_merge($_GET, ['print' => 1])) ?>">Print</a>
      <a class="btn btn-outline-primary" href="/export_report.php?<?= http_build_query(array_merge($_GET, ['report' => 'admin', 'format' => 'pdf'])) ?>">Export PDF</a>
      <a class="btn btn-outline-success" href="/export_report.php?<?= http_build_query(array_merge($_GET, ['report' => 'admin', 'format' => 'xlsx'])) ?>">Export Excel</a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>S.No.</th>
          <th>Thana</th>
          <th>Total complaint number</th>
          <th>Total fraud amount</th>
          <th>Total hold amount</th>
          <th>Total hold amount (%)</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach ($list as $r): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= sanitize(pretty_thana($r['thana'])) ?></td>
          <td><?= sanitize($r['complaint_count'] ?? 0) ?></td>
          <td><?= sanitize(number_format((float)($r['total_fraud'] ?? 0),2)) ?></td>
          <td><?= sanitize(number_format((float)($r['total_hold'] ?? 0),2)) ?></td>
          <td><?= sanitize(number_format((float)($r['hold_percentage'] ?? 0),2)) ?>%</td>
        </tr>
        <?php endforeach; ?>
        <tr class="table-secondary">
          <td colspan="2"><strong>Grand Total</strong></td>
          <td><?= sanitize($gt['complaint_count']) ?></td>
          <td><?= sanitize(number_format($gt['total_fraud'],2)) ?></td>
          <td><?= sanitize(number_format($gt['total_hold'],2)) ?></td>
          <td><?= sanitize($gt['total_fraud'] > 0 ? number_format(100.0 * $gt['total_hold'] / $gt['total_fraud'],2) : '0.00') ?>%</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>