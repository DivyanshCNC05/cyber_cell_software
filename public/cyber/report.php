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

$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';

// debug: log generate requests
error_log('DEBUG: cyber/report.php called - from=' . $from . ' to=' . $to . ' acting_user=' . ($acting_user ?? '') . ' REQUEST_URI=' . ($_SERVER['REQUEST_URI'] ?? ''));
echo '<!-- DEBUG: cyber_report: from=' . htmlspecialchars($from) . ' to=' . htmlspecialchars($to) . ' acting_user=' . htmlspecialchars($acting_user ?? '') . ' REQUEST_URI=' . htmlspecialchars($_SERVER['REQUEST_URI'] ?? '') . ' -->';

$rows = [];
$grand = [
  'total_complaints' => 0,
  'total_acknowledgement' => 0,
  'total_fraud' => 0.0,
  'total_hold' => 0.0,
  'total_refund_amount' => 0.0,
  'total_digital_arrest' => 0,
  'total_digital_amount' => 0.0,
  'total_mobile_number' => 0,
];

function fnum($v) {
  return number_format((float)$v, 2, '.', '');
}

if ($from !== '' && $to !== '') {
  foreach ($allowed as $thanaKey) {

    if (!isset($CYBER_TABLES[$thanaKey])) continue;
    $table = $CYBER_TABLES[$thanaKey];

    // Refund Amount is derived from court_order (since refund_amount column doesn't exist)
    $sql = "SELECT
              COUNT(*) AS total_complaints,
              COALESCE(COUNT(acknowledgement_number), 0) AS total_acknowledgement,
              COALESCE(SUM(total_fraud), 0) AS total_fraud,
              COALESCE(SUM(hold_amount), 0) AS total_hold,
              COALESCE(SUM(court_order), 0) AS total_refund_amount,
              COALESCE(SUM(digital_arrest), 0) AS total_digital_arrest,
              COALESCE(SUM(digital_amount), 0) AS total_digital_amount,
              COALESCE(COUNT(mobile_number), 0) AS total_mobile_number
            FROM {$table}
            WHERE complaint_date BETWEEN :from AND :to";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':from' => $from, ':to' => $to]);
    $agg = $stmt->fetch(PDO::FETCH_ASSOC);

    $totalComplaints      = (int)($agg['total_complaints'] ?? 0);
    $totalAcknowledgement = (int)($agg['total_acknowledgement'] ?? 0);
    $totalFraud           = (float)($agg['total_fraud'] ?? 0);
    $totalHold            = (float)($agg['total_hold'] ?? 0);
    $totalRefund          = (float)($agg['total_refund_amount'] ?? 0);
    $totalDigitalArrest   = (int)($agg['total_digital_arrest'] ?? 0);
    $totalDigitalAmount   = (float)($agg['total_digital_amount'] ?? 0);
    $totalMobileNumber    = (int)($agg['total_mobile_number'] ?? 0);

    // include only if this thana has any record in the range
    if ($totalComplaints > 0) {
      $rows[] = [
        'thana_key' => $thanaKey,
        'thana' => cyber_thana_label($thanaKey),
        'total_complaints' => $totalComplaints,
        'total_acknowledgement' => $totalAcknowledgement,
        'total_fraud' => $totalFraud,
        'total_hold' => $totalHold,
        'total_refund_amount' => $totalRefund,
        'total_digital_arrest' => $totalDigitalArrest,
        'total_digital_amount' => $totalDigitalAmount,
        'total_mobile_number' => $totalMobileNumber,
      ];

      $grand['total_complaints'] += $totalComplaints;
      $grand['total_acknowledgement'] += $totalAcknowledgement;
      $grand['total_fraud'] += $totalFraud;
      $grand['total_hold'] += $totalHold;
      $grand['total_refund_amount'] += $totalRefund;
      $grand['total_digital_arrest'] += $totalDigitalArrest;
      $grand['total_digital_amount'] += $totalDigitalAmount;
      $grand['total_mobile_number'] += $totalMobileNumber;
    }
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cyber Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @media print {
      .no-print { display: none !important; }
      body { background: #fff !important; }
    }
  </style>
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Cyber Report (Thana-wise)</h3>
    <div class="no-print">
      <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_PATH ?>/dashboards/user<?= (int)$acting_user ?>.php">Back</a>
      <?php if ($from && $to): ?>
        <button class="btn btn-dark btn-sm" onclick="window.print()">Print</button>
      <?php endif; ?>
    </div>
  </div>

  <form class="row g-2 mb-3 no-print" method="get">
    <input type="hidden" name="as_user" value="<?= (int)$acting_user ?>">
    <div class="col-md-3">
      <label class="form-label">From</label>
      <input type="date" class="form-control" name="from" value="<?= htmlspecialchars($from) ?>" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">To</label>
      <input type="date" class="form-control" name="to" value="<?= htmlspecialchars($to) ?>" required>
    </div>
    <div class="col-md-3 d-grid align-items-end">
      <button class="btn btn-primary mt-4" type="submit">Generate</button>
    </div>
  </form>

  <?php if ($from && $to): ?>
    <p class="text-muted">Date Range: <strong><?= htmlspecialchars($from) ?></strong> to <strong><?= htmlspecialchars($to) ?></strong></p>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>S.No.</th>
          <th>Thana</th>
          <th>Total Complaint</th>
          <th>Total acknowledgment</th>
          <th>Total Fraud Amount</th>
          <th>Total Hold Amount</th>
          <th>Total Refund Amount</th>
          <th>Total Digital Arrest</th>
          <th>Total Digital Amount</th>
          <th>Total Mobile number</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$from || !$to): ?>
        <tr><td colspan="10" class="text-center">Select From and To date, then click Generate.</td></tr>

      <?php elseif (!$rows): ?>
        <tr><td colspan="10" class="text-center">No data found for this date range.</td></tr>

      <?php else: ?>
        <?php foreach ($rows as $i => $r): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($r['thana']) ?></td>
            <td><?= (int)$r['total_complaints'] ?></td>
            <td><?= (int)$r['total_acknowledgement'] ?></td>
            <td><?= fnum($r['total_fraud']) ?></td>
            <td><?= fnum($r['total_hold']) ?></td>
            <td><?= fnum($r['total_refund_amount']) ?></td>
            <td><?= (int)$r['total_digital_arrest'] ?></td>
            <td><?= fnum($r['total_digital_amount']) ?></td>
            <td><?= (int)$r['total_mobile_number'] ?></td>
          </tr>
        <?php endforeach; ?>

        <tr class="table-warning fw-bold">
          <td colspan="2">Grand Total</td>
          <td><?= (int)$grand['total_complaints'] ?></td>
          <td><?= (int)$grand['total_acknowledgement'] ?></td>
          <td><?= fnum($grand['total_fraud']) ?></td>
          <td><?= fnum($grand['total_hold']) ?></td>
          <td><?= fnum($grand['total_refund_amount']) ?></td>
          <td><?= (int)$grand['total_digital_arrest'] ?></td>
          <td><?= fnum($grand['total_digital_amount']) ?></td>
          <td><?= (int)$grand['total_mobile_number'] ?></td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>
</body>
</html>
