<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/ceir_thanas.php';

// Allow ADMIN to access CEIR pages
if (($_SESSION['role'] ?? '') === 'ADMIN') {
  // admin allowed
} else {
  require_role('CEIR_USER');
}

$allThanas = array_keys($CEIR_TABLES);

// selected thana (default first)
$thana = $_GET['thana'] ?? $allThanas[0];
if (!isset($CEIR_TABLES[$thana])) $thana = $allThanas[0];

$table = $CEIR_TABLES[$thana];

// optional filters
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';
$imei = trim($_GET['imei'] ?? '');

$updated = isset($_GET['updated']);
$deleted = isset($_GET['deleted']);


$where = [];
$params = [];

if ($from !== '') { $where[] = "date_of_complaint >= :from"; $params[':from'] = $from; }
if ($to !== '')   { $where[] = "date_of_complaint <= :to";   $params[':to']   = $to; }
if ($imei !== '') { $where[] = "imei LIKE :imei";            $params[':imei'] = "%{$imei}%"; }

$whereSql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

$sql = "SELECT ceir_id, name, father_name, date_of_complaint, mobile_number,
               imei, lost_found, block_unblock, pdf_attach, created_at
        FROM {$table}
        {$whereSql}
        ORDER BY ceir_id DESC
        LIMIT 200";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CEIR List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">CEIR List</h3>
    <div>
      <a class="btn btn-primary btn-sm" href="<?= BASE_PATH ?>/ceir/add.php">Add CEIR</a>
      <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_PATH ?>/dashboards/ceir.php">Back</a> 
    </div>
  </div>

  <form class="row g-2 mb-3" method="get">
    <div class="col-md-4">
      <label class="form-label">Thana</label>
      <select class="form-select" name="thana">
        <?php foreach ($CEIR_TABLES as $key => $tbl): ?>
          <option value="<?= htmlspecialchars($key) ?>" <?= ($key === $thana) ? 'selected' : '' ?>>
            <?= htmlspecialchars(strtoupper(str_replace('_',' ', $key))) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-2">
      <label class="form-label">From</label>
      <input type="date" class="form-control" name="from" value="<?= htmlspecialchars($from) ?>">
    </div>

    <div class="col-md-2">
      <label class="form-label">To</label>
      <input type="date" class="form-control" name="to" value="<?= htmlspecialchars($to) ?>">
    </div>

    <div class="col-md-2">
      <label class="form-label">IMEI</label>
      <input class="form-control" name="imei" value="<?= htmlspecialchars($imei) ?>" placeholder="Search IMEI">
    </div>

    <div class="col-md-2 d-grid align-items-end">
      <button class="btn btn-dark mt-4" type="submit">Filter</button>
    </div>
  </form>

  <div class="table-responsive">

  <?php if ($updated): ?><div class="alert alert-success">Record updated.</div><?php endif; ?>
<?php if ($deleted): ?><div class="alert alert-success">Record deleted.</div><?php endif; ?>

    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Father</th>
          <th>Date</th>
          <th>Mobile</th>
          <th>IMEI</th>
          <th>Lost/Found</th>
          <th>Block</th>
          <th>PDF</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$rows): ?>
        <tr><td colspan="10" class="text-center">No records found</td></tr>
      <?php else: ?>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['ceir_id'] ?></td>
            <td><?= htmlspecialchars($r['name'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['father_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['date_of_complaint'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['mobile_number'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['imei'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['lost_found'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['block_unblock'] ?? '') ?></td>
            <td>
              <?php if (!empty($r['pdf_attach'])): ?>
                <a class="btn btn-sm btn-info"
                   target="_blank"
                   href="<?= BASE_PATH ?>/ceir/view_pdf.php?thana=<?= urlencode($thana) ?>&ceir_id=<?= (int)$r['ceir_id'] ?>">
                   View PDF
                </a>
              <?php else: ?>
                <span class="text-muted">No PDF</span>
              <?php endif; ?>
            </td>
            <td>
              <a class="btn btn-sm btn-warning"
                 href="<?= BASE_PATH ?>/ceir/edit.php?thana=<?= urlencode($thana) ?>&ceir_id=<?= (int)$r['ceir_id'] ?>">Edit</a>
              <a class="btn btn-sm btn-danger"
                 href="<?= BASE_PATH ?>/ceir/delete.php?thana=<?= urlencode($thana) ?>&ceir_id=<?= (int)$r['ceir_id'] ?>">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <p class="text-muted small mb-0">Showing latest 200 records.</p>

</div>
</body>
</html>
