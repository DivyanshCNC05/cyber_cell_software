<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/thanas.php';
require __DIR__ . '/access.php';

require_role('ADMIN');

$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';

$rows = [];
$grand = [
  'complaints' => 0,
  'fraud' => 0.0,
  'hold' => 0.0,
  'refund' => 0.0,
];

function f2($v){ return number_format((float)$v, 2, '.', ''); }

if ($from && $to) {
  foreach ($CYBER_TABLES as $thanaKey => $table) {
    $sql = "SELECT
              COUNT(*) AS total_complaints,
              COALESCE(SUM(total_fraud), 0) AS total_fraud,
              COALESCE(SUM(hold_amount), 0) AS total_hold,
              COALESCE(SUM(refund_amount), 0) AS total_refund
            FROM {$table}
            WHERE complaint_date BETWEEN :from AND :to";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':from' => $from, ':to' => $to]);
    $a = $stmt->fetch();

    $c = (int)($a['total_complaints'] ?? 0);
    if ($c > 0) {
      $fraud = (float)($a['total_fraud'] ?? 0);
      $hold  = (float)($a['total_hold'] ?? 0);
      $ref   = (float)($a['total_refund'] ?? 0);
      $holdPct = ($fraud > 0) ? round(($hold / $fraud) * 100, 2) : 0;

      $rows[] = [
        'thana' => cyber_thana_label($thanaKey),
        'complaints' => $c,
        'fraud' => $fraud,
        'hold' => $hold,
        'refund' => $ref,
        'hold_pct' => $holdPct,
      ];

      $grand['complaints'] += $c;
      $grand['fraud'] += $fraud;
      $grand['hold'] += $hold;
      $grand['refund'] += $ref;
    }
  }
}

$grandHoldPct = ($grand['fraud'] > 0) ? round(($grand['hold'] / $grand['fraud']) * 100, 2) : 0;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Cyber Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>@media print{.no-print{display:none!important;}}</style>
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Admin Cyber Report (All Thanas)</h3>
    <div class="no-print">
      <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_PATH ?>/dashboards/admin.php">Back</a>
      <?php if ($from && $to): ?>
        <button class="btn btn-dark btn-sm" onclick="window.print()">Print</button>
      <?php endif; ?>
    </div>
  </div>

  <form class="row g-2 mb-3 no-print" method="get">
    <div class="col-md-3">
      <label class="form-label">From</label>
      <input type="date" class="form-control" name="from" value="<?= htmlspecialchars($from) ?>" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">To</label>
      <input type="date" class="form-control" name="to" value="<?= htmlspecialchars($to) ?>" required>
    </div>
    <div class="col-md-2 d-grid align-items-end">
      <button class="btn btn-primary mt-4" type="submit">Generate</button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>S.No.</th>
          <th>Thana</th>
          <th>Total Complaints</th>
          <th>Total Fraud</th>
          <th>Total Hold</th>
          <th>Total Refund</th>
          <th>Hold %</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$from || !$to): ?>
        <tr><td colspan="7" class="text-center">Select From and To date, then click Generate.</td></tr>
      <?php elseif (!$rows): ?>
        <tr><td colspan="7" class="text-center">No data found for this date range.</td></tr>
      <?php else: ?>
        <?php foreach ($rows as $i => $r): ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($r['thana']) ?></td>
            <td><?= (int)$r['complaints'] ?></td>
            <td><?= f2($r['fraud']) ?></td>
            <td><?= f2($r['hold']) ?></td>
            <td><?= f2($r['refund']) ?></td>
            <td><?= f2($r['hold_pct']) ?>%</td>
          </tr>
        <?php endforeach; ?>

        <tr class="table-warning fw-bold">
          <td colspan="2">Grand Total</td>
          <td><?= (int)$grand['complaints'] ?></td>
          <td><?= f2($grand['fraud']) ?></td>
          <td><?= f2($grand['hold']) ?></td>
          <td><?= f2($grand['refund']) ?></td>
          <td><?= f2($grandHoldPct) ?>%</td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>
</body>
</html>
