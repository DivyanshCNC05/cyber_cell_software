<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/tables.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['ADMIN','CEIR_USER']);
$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;
$print = isset($_GET['print']);
$list = report_ceir_by_month_thana($from, $to);
// compute totals
$gt = ['lost'=>0,'found'=>0,'blocks'=>0];
foreach ($list as $r) {
    $gt['lost'] += (int)($r['lost'] ?? 0);
    $gt['found'] += (int)($r['found'] ?? 0);
    $gt['blocks'] += (int)($r['blocks'] ?? 0);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CEIR Report</title>
  <?php if (!$print): ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <?php endif; ?>
</head>
<body <?php if ($print) echo 'onload="window.print()"'; ?> class="bg-light">
<div class="container py-4">
  <h3>CEIR Report</h3>
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
      <a class="btn btn-outline-primary" href="/export_report.php?<?= http_build_query(array_merge($_GET, ['report' => 'ceir', 'format' => 'pdf'])) ?>">Export PDF</a>
      <a class="btn btn-outline-success" href="/export_report.php?<?= http_build_query(array_merge($_GET, ['report' => 'ceir', 'format' => 'xlsx'])) ?>">Export Excel</a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>S.No.</th>
          <th>Month</th>
          <th>Thana</th>
          <th>Total Lost mobiles</th>
          <th>Total found mobile</th>
          <th>Block/unblock</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach ($list as $r): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= sanitize($r['ym']) ?></td>
          <td><?= sanitize(pretty_thana($r['thana'])) ?></td>
          <td><?= sanitize($r['lost'] ?? 0) ?></td>
          <td><?= sanitize($r['found'] ?? 0) ?></td>
          <td><?= sanitize($r['blocks'] ?? 0) ?></td>
        </tr>
        <?php endforeach; ?>
        <tr class="table-secondary">
          <td colspan="3"><strong>Grand Total</strong></td>
          <td><?= sanitize($gt['lost']) ?></td>
          <td><?= sanitize($gt['found']) ?></td>
          <td><?= sanitize($gt['blocks']) ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>