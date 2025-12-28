<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/ceir_thanas.php';
require __DIR__ . 'access.php';

require_role('ADMIN');

$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';

$rows = [];
$grand = ['total'=>0,'lost'=>0,'found'=>0,'block'=>0,'unblock'=>0,'pdf'=>0];

if ($from && $to) {
  foreach ($CEIR_TABLES as $thanaKey => $table) {

    $sql = "SELECT
              COUNT(*) AS total,
              SUM(CASE WHEN lost_found = 'LOST' THEN 1 ELSE 0 END) AS lost,
              SUM(CASE WHEN lost_found = 'FOUND' THEN 1 ELSE 0 END) AS found,
              SUM(CASE WHEN block_unblock = 'BLOCK' THEN 1 ELSE 0 END) AS block_cnt,
              SUM(CASE WHEN block_unblock = 'UNBLOCK' THEN 1 ELSE 0 END) AS unblock_cnt,
              SUM(CASE WHEN pdf_attach IS NOT NULL AND pdf_attach <> '' THEN 1 ELSE 0 END) AS pdf_cnt
            FROM {$table}
            WHERE date_of_complaint BETWEEN :from AND :to";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':from' => $from, ':to' => $to]);
    $a = $stmt->fetch();

    $t = (int)($a['total'] ?? 0);
    if ($t > 0) {
      $lost = (int)($a['lost'] ?? 0);
      $found = (int)($a['found'] ?? 0);
      $block = (int)($a['block_cnt'] ?? 0);
      $unblock = (int)($a['unblock_cnt'] ?? 0);
      $pdf = (int)($a['pdf_cnt'] ?? 0);

      $rows[] = [
        'thana' => strtoupper(str_replace('_',' ', $thanaKey)),
        'total' => $t,
        'lost' => $lost,
        'found' => $found,
        'block' => $block,
        'unblock' => $unblock,
        'pdf' => $pdf,
      ];

      $grand['total'] += $t;
      $grand['lost'] += $lost;
      $grand['found'] += $found;
      $grand['block'] += $block;
      $grand['unblock'] += $unblock;
      $grand['pdf'] += $pdf;
    }
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin CEIR Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>@media print{.no-print{display:none!important;}}</style>
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Admin CEIR Report (All Thanas)</h3>
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
          <th>Total</th>
          <th>LOST</th>
          <th>FOUND</th>
          <th>BLOCK</th>
          <th>UNBLOCK</th>
          <th>PDF</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$from || !$to): ?>
        <tr><td colspan="8" class="text-center">Select From and To date, then click Generate.</td></tr>
      <?php elseif (!$rows): ?>
        <tr><td colspan="8" class="text-center">No data found for this date range.</td></tr>
      <?php else: ?>
        <?php foreach ($rows as $i => $r): ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($r['thana']) ?></td>
            <td><?= (int)$r['total'] ?></td>
            <td><?= (int)$r['lost'] ?></td>
            <td><?= (int)$r['found'] ?></td>
            <td><?= (int)$r['block'] ?></td>
            <td><?= (int)$r['unblock'] ?></td>
            <td><?= (int)$r['pdf'] ?></td>
          </tr>
        <?php endforeach; ?>

        <tr class="table-warning fw-bold">
          <td colspan="2">Grand Total</td>
          <td><?= (int)$grand['total'] ?></td>
          <td><?= (int)$grand['lost'] ?></td>
          <td><?= (int)$grand['found'] ?></td>
          <td><?= (int)$grand['block'] ?></td>
          <td><?= (int)$grand['unblock'] ?></td>
          <td><?= (int)$grand['pdf'] ?></td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>
</body>
</html>
