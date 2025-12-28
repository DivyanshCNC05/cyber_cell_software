<?php
require __DIR__ . '../../includes/db.php';
require __DIR__ . '../../includes/auth.php';
require __DIR__ . '../../includes/thanas.php';
require __DIR__ . 'access.php';


require_role('ADMIN');

$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';

function f2($v){ return number_format((float)$v, 2, '.', ''); }

$GROUPS = [
  'CSP Dewas' => ['kotwali','civil_line','industrial_area','nahar_darwaja'],
  'SDOP Sonkach' => ['sonkatch','pipalrawan','bhaurasa','tonkkhurd'],
  'SDOP Bhaghli' => ['bagli','udai_nagar','hatpiplya','kantaphod','kamlapur'],
  'SDOP Kannod' => ['kannod','khategaon','satwas','nemawar','harangaon'],
  'DSP Headquatar' => ['bank_note_press','vijayganj_mandi','barotha'],
];

$report = [];
$grand = ['complaints'=>0,'fraud'=>0.0,'hold'=>0.0,'refund'=>0.0];

if ($from && $to) {
  foreach ($GROUPS as $groupName => $thanaKeys) {

    $totComplaints = 0;
    $totFraud = 0.0;
    $totHold = 0.0;
    $totRefund = 0.0;

    foreach ($thanaKeys as $key) {
      if (!isset($CYBER_TABLES[$key])) continue; // safety whitelist [web:294]
      $table = $CYBER_TABLES[$key];

      $sql = "SELECT
                COUNT(*) AS c,
                COALESCE(SUM(total_fraud), 0) AS tf,
                COALESCE(SUM(hold_amount), 0) AS th,
                COALESCE(SUM(refund_amount), 0) AS tr
              FROM {$table}
              WHERE complaint_date BETWEEN :from AND :to";

      $stmt = $pdo->prepare($sql);
      $stmt->execute([':from' => $from, ':to' => $to]);
      $a = $stmt->fetch();

      $totComplaints += (int)($a['c'] ?? 0);
      $totFraud      += (float)($a['tf'] ?? 0);
      $totHold       += (float)($a['th'] ?? 0);
      $totRefund     += (float)($a['tr'] ?? 0);
    }

    $holdPct = ($totFraud > 0) ? round(($totHold / $totFraud) * 100, 2) : 0;

    $report[] = [
      'group' => $groupName,
      'complaints' => $totComplaints,
      'fraud' => $totFraud,
      'hold' => $totHold,
      'refund' => $totRefund,
      'hold_pct' => $holdPct,
    ];

    $grand['complaints'] += $totComplaints;
    $grand['fraud'] += $totFraud;
    $grand['hold'] += $totHold;
    $grand['refund'] += $totRefund;
  }
}

$grandHoldPct = ($grand['fraud'] > 0) ? round(($grand['hold'] / $grand['fraud']) * 100, 2) : 0;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Group Cyber Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>@media print{.no-print{display:none!important;}}</style>
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Admin Cyber Report (Group-wise)</h3>
    <div class="no-print">
      <a class="btn btn-outline-secondary btn-sm" href="/dashboards/admin.php">Back</a>
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
          <th>Thana Group</th>
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

      <?php elseif (!$report): ?>
        <tr><td colspan="7" class="text-center">No data found for this date range.</td></tr>

      <?php else: ?>
        <?php foreach ($report as $i => $r): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($r['group']) ?></td>
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
